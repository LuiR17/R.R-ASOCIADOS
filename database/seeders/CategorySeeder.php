<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bombas Oleohidráulicas',
                'slug' => 'bombas-oleohidraulicas',
                'icon' => 'settings_input_component',
                'description' => 'Paletas, Pistones, Volteo con acople directo y cardánico. Simples, dobles y triples.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Bombas y Motores de Aluminio',
                'slug' => 'bombas-motores-aluminio',
                'icon' => 'motion_sensor_active',
                'description' => 'Bombas de engranajes en cuerpos de aluminio y motores oleohidráulicos industriales y móviles.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Electroválvulas',
                'slug' => 'electrovalvulas',
                'icon' => 'settings_ethernet',
                'description' => 'Electroválvulas direccionales, proporcionales y de control en todos los tamaños y voltajes.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Válvulas Hidráulicas',
                'slug' => 'valvulas-hidraulicas',
                'icon' => 'tune',
                'description' => 'Reductoras de presión, reguladoras de caudal, mandos manuales, alivio, check y contrabalance.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Cilindros Hidráulicos',
                'slug' => 'cilindros-hidraulicos',
                'icon' => 'settings_input_svideo',
                'description' => 'Simples y doble efecto, telescópicos de 3, 4 y 5 etapas, tubos honeados, sellos y ejes cromados.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Acumuladores y Filtración',
                'slug' => 'acumuladores-filtracion',
                'icon' => 'filter_alt',
                'description' => 'Acumuladores de nitrógeno, kits de carga, vejigas y elementos filtrantes hidráulicos.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Intercambiadores de Calor',
                'slug' => 'intercambiadores-calor',
                'icon' => 'ac_unit',
                'description' => 'Enfriadores de aceite hidráulico a base de aire y agua para uso industrial pesado.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Partes y Accesorios',
                'slug' => 'partes-accesorios',
                'icon' => 'extension',
                'description' => 'Piezas de bombas y motores, manómetros, acoples y accesorios para sistemas hidráulicos.',
                'sort_order' => 8,
            ],
            [
                'name' => 'Centrales Hidráulicas',
                'slug' => 'centrales-hidraulicas',
                'icon' => 'settings_input_hdmi',
                'description' => 'Unidades de potencia compactas e industriales a medida.',
                'sort_order' => 9,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Clear cached categories so the public catalog picks up changes immediately
        Cache::forget('catalog_categories_list');
    }
}
