<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\DocumentModel;
use MongoDB\Laravel\Relations\BelongsTo;
use App\Models\Boutique;

class Salle extends EloquentModel
{
    use HasFactory;
    use DocumentModel;

    protected $connection = 'mongodb';
    protected string $collection = 'salles';

    protected $fillable = [
        'nom',
        'capacite',
        'categorie',
        'boutique_id',
    ];

    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }

    public function ateliers()
    {
        return $this->hasMany(Atelier::class);
    }

    /**
     * Vérifie si la salle est occupée à un moment donné
     *
     * @param \Carbon\Carbon|string $dateTime
     * @return bool
     */
    public function isOccupiedAt($dateTime)
    {
        $dateTime = \Carbon\Carbon::parse($dateTime);

        // Récupérer tous les ateliers de cette salle (convertir l'ID en ObjectId)
        $salleId = is_string($this->_id) ? new \MongoDB\BSON\ObjectId($this->_id) : $this->_id;
        $ateliers = Atelier::where('salle_id', $salleId)->get();

        foreach ($ateliers as $atelier) {
            if (!$atelier->date || !$atelier->duree) {
                continue;
            }

            $atelierStart = \Carbon\Carbon::parse($atelier->date);
            $duree = is_numeric($atelier->duree) ? (int) $atelier->duree : 0;
            $atelierEnd = $atelierStart->copy()->addHours($duree);

            // Vérifier si le moment donné se trouve dans la plage de l'atelier
            if ($dateTime->between($atelierStart, $atelierEnd, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Récupère les prochains ateliers de la salle
     *
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getProchainAteliers($limit = 10)
    {
        // Convertir l'ID en ObjectId si c'est une string
        $salleId = is_string($this->_id) ? new \MongoDB\BSON\ObjectId($this->_id) : $this->_id;

        return Atelier::where('salle_id', $salleId)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Récupère l'atelier en cours (si la salle est occupée maintenant)
     *
     * @return Atelier|null
     */
    public function getAtelierEnCours()
    {
        $now = now();
        // Convertir l'ID en ObjectId si c'est une string
        $salleId = is_string($this->_id) ? new \MongoDB\BSON\ObjectId($this->_id) : $this->_id;
        $ateliers = Atelier::where('salle_id', $salleId)->get();

        foreach ($ateliers as $atelier) {
            if (!$atelier->date || !$atelier->duree) {
                continue;
            }

            $atelierStart = \Carbon\Carbon::parse($atelier->date);
            $duree = is_numeric($atelier->duree) ? (int) $atelier->duree : 0;
            $atelierEnd = $atelierStart->copy()->addHours($duree);

            if ($now->between($atelierStart, $atelierEnd, true)) {
                return $atelier;
            }
        }

        return null;
    }

    /**
     * Génère un calendrier d'occupation pour les N prochains jours
     *
     * @param int $days
     * @return array
     */
    public function getCalendrierOccupation($days = 7)
    {
        $calendrier = [];
        $startDate = now()->startOfDay();

        // Convertir l'ID en ObjectId si c'est une string
        $salleId = is_string($this->_id) ? new \MongoDB\BSON\ObjectId($this->_id) : $this->_id;

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $ateliers = Atelier::where('salle_id', $salleId)
                ->whereDate('date', $date)
                ->orderBy('date', 'asc')
                ->get();

            $calendrier[] = [
                'date' => $date,
                'ateliers' => $ateliers,
                'count' => $ateliers->count(),
            ];
        }

        return $calendrier;
    }

    /**
     * Vérifie si la salle est disponible pour un créneau donné (date + durée)
     *
     * @param \Carbon\Carbon|string $dateTime
     * @param int $duree Durée en heures
     * @param string|null $atelierIdToExclude ID de l'atelier à exclure (pour les mises à jour)
     * @return bool
     */
    public function isDisponible($dateTime, $duree, $atelierIdToExclude = null)
    {
        $dateTime = \Carbon\Carbon::parse($dateTime);
        $duree = is_numeric($duree) ? (int) $duree : 0;
        $finAtelier = $dateTime->copy()->addHours($duree);

        // Convertir l'ID en ObjectId si c'est une string
        $salleId = is_string($this->_id) ? new \MongoDB\BSON\ObjectId($this->_id) : $this->_id;

        // Récupérer tous les ateliers de cette salle
        $query = Atelier::where('salle_id', $salleId);

        // Exclure l'atelier en cours de modification
        if ($atelierIdToExclude) {
            $query->where('_id', '!=', new \MongoDB\BSON\ObjectId((string) $atelierIdToExclude));
        }

        $ateliers = $query->get();

        foreach ($ateliers as $atelier) {
            if (!$atelier->date || !$atelier->duree) {
                continue;
            }

            $atelierStart = \Carbon\Carbon::parse($atelier->date);
            $atelierDuree = is_numeric($atelier->duree) ? (int) $atelier->duree : 0;
            $atelierEnd = $atelierStart->copy()->addHours($atelierDuree);

            // Vérifier s'il y a chevauchement
            // Chevauchement si : (début1 < fin2) ET (fin1 > début2)
            if ($dateTime->lt($atelierEnd) && $finAtelier->gt($atelierStart)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Récupère toutes les salles disponibles pour un créneau donné
     *
     * @param \Carbon\Carbon|string $dateTime
     * @param int $duree Durée en heures
     * @param string|null $atelierIdToExclude ID de l'atelier à exclure
     * @return \Illuminate\Support\Collection
     */
    public static function getSallesDisponibles($dateTime, $duree, $atelierIdToExclude = null)
    {
        $salles = self::all();

        return $salles->filter(function ($salle) use ($dateTime, $duree, $atelierIdToExclude) {
            return $salle->isDisponible($dateTime, $duree, $atelierIdToExclude);
        });
    }
}
