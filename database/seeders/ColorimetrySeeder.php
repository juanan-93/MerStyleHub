<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Colorimetry;

class ColorimetrySeeder extends Seeder
{
    public function run(): void
    {
        $colorimetries = [
            [
                'name' => 'Primavera cálida',
                'description' => 'Colores cálidos, luminosos y vibrantes. Ideal para tonos dorados, melocotón y coral.'
            ],
            [
                'name' => 'Primavera brillante',
                'description' => 'Colores vivos, claros y saturados. Funciona bien con tonos brillantes y puros.'
            ],
            [
                'name' => 'Primavera clara',
                'description' => 'Colores suaves, pasteles y delicados. Tonos cálidos pero ligeros.'
            ],
            [
                'name' => 'Verano frío',
                'description' => 'Colores fríos, suaves y apagados. Ideal para tonos azulados y grises.'
            ],
            [
                'name' => 'Verano suave',
                'description' => 'Colores neutros, apagados y poco saturados. Tonos polvorientos y suaves.'
            ],
            [
                'name' => 'Verano claro',
                'description' => 'Colores pasteles fríos y delicados. Tonos claros con subtono azul.'
            ],
            [
                'name' => 'Otoño cálido',
                'description' => 'Colores tierra, cálidos y ricos. Ideal para tonos terracota, mostaza y verde oliva.'
            ],
            [
                'name' => 'Otoño oscuro',
                'description' => 'Colores profundos, cálidos y saturados. Tonos chocolate, burdeos y verde bosque.'
            ],
            [
                'name' => 'Otoño suave',
                'description' => 'Colores apagados, cálidos y neutros. Tonos tierra suaves y poco saturados.'
            ],
            [
                'name' => 'Invierno oscuro',
                'description' => 'Colores intensos, fríos y profundos. Ideal para negro, azul marino y púrpura oscuro.'
            ],
            [
                'name' => 'Invierno frío',
                'description' => 'Colores fríos, puros y saturados. Tonos azules, plateados y fucsia.'
            ],
            [
                'name' => 'Invierno brillante',
                'description' => 'Colores vivos, fríos y de alto contraste. Tonos brillantes como magenta y azul eléctrico.'
            ],
        ];

        foreach ($colorimetries as $colorimetry) {
            Colorimetry::create($colorimetry);
        }
    }
}
