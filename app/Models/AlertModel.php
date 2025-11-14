<?php

namespace App\Models;

use CodeIgniter\Model;

class AlertModel extends Model
{
    protected $table = 'alerts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'branch_id',
        'item_id',
        'alert_type',
        'title',
        'message',
        'severity',
        'status',
        'acknowledged_by',
        'acknowledged_at',
        'resolved_at',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getActiveAlerts($branchId = null)
    {
        $builder = $this->where('status', 'active');

        if ($branchId !== null) {
            $builder->where('branch_id', $branchId);
        }

        return $builder->orderBy('severity', 'DESC')
                      ->orderBy('created_at', 'DESC')
                      ->findAll();
    }


    public function getAlertsByType($type, $status = 'active', $branchId = null)
    {
        $builder = $this->where('alert_type', $type)
                       ->where('status', $status);

        if ($branchId !== null) {
            $builder->where('branch_id', $branchId);
        }

        return $builder->findAll();
    }

    public function acknowledgeAlert($alertId, $userId)
    {
        return $this->update($alertId, [
            'status' => 'acknowledged',
            'acknowledged_by' => $userId,
            'acknowledged_at' => date('Y-m-d H:i:s')
        ]);
    }


    public function resolveAlert($alertId)
    {
        return $this->update($alertId, [
            'status' => 'resolved',
            'resolved_at' => date('Y-m-d H:i:s')
        ]);
    }


    public function createAlert($data)
    {
        // Check for existing active alert first
        $existingActive = $this->where('alert_type', $data['alert_type'])
                              ->where('status', 'active')
                              ->where('branch_id', $data['branch_id'] ?? null)
                              ->where('item_id', $data['item_id'] ?? null)
                              ->first();

        if ($existingActive) {
            // Update existing active alert
            return $this->update($existingActive['id'], [
                'message' => $data['message'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Check if there's a recently acknowledged alert (within last 24 hours)
        // Don't create new alerts for recently acknowledged issues
        $recentlyAcknowledged = $this->where('alert_type', $data['alert_type'])
                                    ->where('status', 'acknowledged')
                                    ->where('branch_id', $data['branch_id'] ?? null)
                                    ->where('item_id', $data['item_id'] ?? null)
                                    ->where('acknowledged_at >', date('Y-m-d H:i:s', strtotime('-24 hours')))
                                    ->first();

        if ($recentlyAcknowledged) {
            // Don't create a new alert if it was recently acknowledged
            return false;
        }

        // Create new alert
        return $this->insert($data);
    }

    public function getAlertCounts($branchId = null)
    {
        $builder = $this->select('severity, COUNT(*) as count')
                       ->where('status', 'active');

        if ($branchId !== null) {
            $builder->where('branch_id', $branchId);
        }

        $results = $builder->groupBy('severity')
                           ->findAll();

        $counts = [
            'low' => 0,
            'medium' => 0,
            'high' => 0,
            'critical' => 0
        ];

        foreach ($results as $result) {
            $counts[$result['severity']] = (int) $result['count'];
        }

        return $counts;
    }
}
