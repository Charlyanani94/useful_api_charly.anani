<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/** */
  use Illuminate\Support\Facades\DB;
  use App\Models\Module;

  /** */


class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create(['name' => 'URL Shortener', 'description' => 'Raccourcir et gérer des liens']);
        Module::create(['name' => 'Wallet', 'description' => 'Gestion du solde et transferts']);
        Module::create(['name' => 'Marketplace + Stock Manager', 'description' => 'Gestion de produits et commandes']);
        Module::create(['name' => 'Time Tracker', 'description' => 'Suivi du temps et sessions']);
        Module::create(['name' => 'Investment Tracker', 'description' => 'Suivi du portefeuille d\'investissement']);
    }


}
