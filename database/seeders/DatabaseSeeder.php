<?php
namespace Database\Seeders;

use App\Models\{User, Category, Supplier, Customer, Mineral};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
  
    $this->call(RolesAndPermissionsSeeder::class);

    // Super admin — no branch (sees all)
    $super = User::firstOrCreate(
        ['email' => 'superadmin@mineralhub.test'],
        [
            'name'              => 'Super Admin',
            'password'          => Hash::make('password'),
            'branch_id'         => null,
            'email_verified_at' => now(),
        ]
    );
    $super->syncRoles(['super-admin']);

    // Admin at Main Branch
    $mainBranch = \App\Models\Branch::where('code', 'MAIN')->first();
    $admin = User::firstOrCreate(
        ['email' => 'admin@mineralhub.test'],
        [
            'name'              => 'Manager',
            'password'          => Hash::make('password'),
            'branch_id'         => $mainBranch->id,
            'email_verified_at' => now(),
        ]
    );
    $admin->syncRoles(['admin']);

    // Cashier at Main Branch
    $cashier = User::firstOrCreate(
        ['email' => 'cashier@mineralhub.test'],
        [
            'name'              => 'Cashier',
            'password'          => Hash::make('password'),
            'branch_id'         => $mainBranch->id,
            'email_verified_at' => now(),
        ]
    );
    $cashier->syncRoles(['cashier']);

        $categories = collect([
            ['name' => 'Gemstones', 'color' => '#d4a24c'],
            ['name' => 'Crystals',  'color' => '#6a9fd8'],
            ['name' => 'Fossils',   'color' => '#8b6e4e'],
            ['name' => 'Ore Specimens', 'color' => '#7d7d7d'],
            ['name' => 'Meteorites','color' => '#3d3d3d'],
            ['name' => 'Decorative', 'color' => '#a86b9a'],
        ])->map(fn($c) => Category::create($c));

        $suppliers = collect([
            ['name' => 'Atlas Minerals Ltd',  'email' => 'sales@atlas.test',  'phone' => '+1-555-0100'],
            ['name' => 'GeoWorld Trading',    'email' => 'orders@geoworld.test','phone' => '+1-555-0200'],
            ['name' => 'Crystal Cave Co',     'email' => 'hello@crystalcave.test'],
            ['name' => 'Stone & Sky',         'email' => 'supply@stoneandsky.test'],
        ])->map(fn($s) => Supplier::create($s));

        $customers = collect([
            ['name' => 'Sarah Klein', 'email' => 'sarah@example.test'],
            ['name' => 'James Okafor','email' => 'james@example.test'],
            ['name' => 'Marta Ruiz',  'email' => 'marta@example.test'],
            ['name' => 'Ahmed Hassan','email' => 'ahmed@example.test'],
            ['name' => 'Lena Fischer','email' => 'lena@example.test'],
        ])->map(fn($c) => Customer::create($c));

        $names = [
            'Amethyst Cluster'   => ['Crystals',    45.00, 120.00, 12, 5],
            'Rose Quartz'        => ['Crystals',    18.00,  49.00, 30, 10],
            'Pyrite Cube'        => ['Ore Specimens', 22.00, 65.00, 8,  15],
            'Malachite Slab'     => ['Decorative',  60.00, 180.00, 11, 6],
            'Fluorite Specimen'  => ['Crystals',    35.00, 95.00, 4,  8],
            'Obsidian Arrowhead' => ['Gemstones',    8.00, 22.00, 55, 15],
            'Meteorite Fragment' => ['Meteorites', 200.00, 480.00, 3, 2],
            'Trilobite Fossil'   => ['Fossils',    120.00, 320.00, 6, 3],
            'Quartz Point'       => ['Crystals',    15.00, 42.00, 40, 20],
            'Tiger Eye Tumble'   => ['Gemstones',    6.00, 18.00, 80, 20],
        ];

        foreach ($names as $name => [$cat, $cost, $price, $qty, $min]) {
            Mineral::create([
                'category_id'   => $categories->firstWhere('name', $cat)->id,
                'supplier_id'   => $suppliers->random()->id,
                'name'          => $name,
                'sku'           => strtoupper(substr(preg_replace('/[^A-Z]/i', '', $name), 0, 3)) . '-' . rand(100, 999),
                'cost_price'    => $cost,
                'sale_price'    => $price,
                'stock_qty'     => $qty,
                'min_stock_qty' => $min,
                'unit'          => 'piece',
                'description'   => "Beautiful {$name} from our premium collection.",
            ]);
        }
    }
}