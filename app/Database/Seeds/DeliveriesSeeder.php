<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DeliveriesSeeder extends Seeder
{
    public function run()
    {
        $now = Time::now()->toDateTimeString();

        $deliveries = [
            [
                'delivery_id' => 'DLV-001',
                'purchase_order_id' => 1,
                'branch_id' => 2,
                'supplier_id' => 1,
                'driver_name' => 'Juan Delivery',
                'driver_phone' => '+63 917 555 1234',
                'vehicle_info' => 'ABC-123 (White Van)',
                'status' => 'arrived', // Ready for claiming
                'scheduled_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
                'departure_time' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'arrival_time' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'notes' => 'Delivery for PO-001 - Branch 2 urgent order',
                'created_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('deliveries')->insertBatch($deliveries);
    }
}