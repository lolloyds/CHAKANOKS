<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DeliveriesSeeder extends Seeder
{
    public function run()
    {
        // Use existing supplier from UsersSeeder (username: supplier_user)
        $supplier = $this->db->table('users')->where('username', 'supplier_user')->get()->getRow();
        $supplierId = $supplier->id ?? 1; // fallback to ID 1 if not found

        // Get actual branches from database
        $branches = $this->db->table('branches')->get()->getResultArray();
        if (empty($branches)) {
            throw new \Exception('No branches found - please run BranchesSeeder first');
        }

        // Sample deliveries - one per branch that exists (2 branches from BranchesSeeder)
        $deliveries = [];
        $statusOptions = ['scheduled', 'in_transit'];

        foreach ($branches as $index => $branch) { // All branches (2 in this case)
            $status = $statusOptions[$index % 3];
            $deliveries[] = [
                'delivery_id' => 'DLV-0' . ($index + 1),
                'branch_id' => $branch['id'],
                'supplier_id' => $supplierId,
                'driver' => isset(['Juan Dela Cruz', 'Pedro Santos', 'Carlos Reyes'][$index]) ?
                    ['Juan Dela Cruz', 'Pedro Santos', 'Carlos Reyes'][$index] : 'Driver ' . ($index + 1),
                'status' => $status,
                'scheduled_time' => $status === 'delivered' ? date('Y-m-d H:i:s', strtotime('-2 hours')) :
                                   ($status === 'in_transit' ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime('+2 hours'))),
                'arrival_time' => $status === 'delivered' ? date('Y-m-d H:i:s', strtotime('-30 minutes')) : null,
                'notes' => 'Daily delivery for ' . $branch['name'],
                'created_by' => 1,
                'approved_by' => null,
                'approved_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        // Check for existing deliveries and skip if they exist
        foreach ($deliveries as $delivery) {
            $exists = $this->db->table('deliveries')->where('delivery_id', $delivery['delivery_id'])->countAllResults();
            if ($exists == 0) {
                $this->db->table('deliveries')->insert($delivery);
            }
        }

        // Get item IDs
        $roastedChicken = $this->db->table('items')->where('name', 'Roasted Chicken')->get()->getRow();
        $friedChicken = $this->db->table('items')->where('name LIKE', '%Fried Chicken%')->get()->getRow();
        $rice = $this->db->table('items')->where('name', 'Rice')->get()->getRow();
        $veggies = $this->db->table('items')->where('name', 'Vegetables')->get()->getRow();

        // Fallback if items don't exist
        if (!$roastedChicken) $roastedChicken = (object)['id' => 1];
        if (!$friedChicken) $friedChicken = (object)['id' => 2];
        if (!$rice) $rice = (object)['id' => 3];
        if (!$veggies) $veggies = (object)['id' => 4];

        // Delivery items
        $deliveryItems = [
            // DLV-001 items
            [
                'delivery_id' => 'DLV-001',
                'item_id' => $roastedChicken->id,
                'quantity' => 50,
                'unit_price' => 150.00,
                'expiry_date' => date('Y-m-d', strtotime('+7 days')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'delivery_id' => 'DLV-001',
                'item_id' => $rice->id,
                'quantity' => 30,
                'unit_price' => 50.00,
                'expiry_date' => date('Y-m-d', strtotime('+30 days')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // DLV-002 items
            [
                'delivery_id' => 'DLV-002',
                'item_id' => $roastedChicken->id,
                'quantity' => 25,
                'unit_price' => 150.00,
                'expiry_date' => date('Y-m-d', strtotime('+7 days')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'delivery_id' => 'DLV-002',
                'item_id' => $veggies->id,
                'quantity' => 20,
                'unit_price' => 75.00,
                'expiry_date' => date('Y-m-d', strtotime('+5 days')),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Insert delivery items
        $this->db->table('delivery_items')->insertBatch($deliveryItems);
    }
}
