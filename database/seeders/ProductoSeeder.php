<?php
namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // MEDICAMENTOS (8)
            [
                'nombre' => 'NexGard 10-25kg',
                'codigo' => 'MED001',
                'categoria' => 'medicamento',
                'precio' => 450.00,
                'stock' => 30,
                'descripcion' => 'Tableta masticable antiparasitaria para perros de 10 a 25 kg. Protege contra pulgas y garrapatas durante 30 días.'
            ],
            [
                'nombre' => 'NexGard 25-50kg',
                'codigo' => 'MED002',
                'categoria' => 'medicamento',
                'precio' => 520.00,
                'stock' => 25,
                'descripcion' => 'Tableta masticable antiparasitaria para perros de 25 a 50 kg. Protege contra pulgas y garrapatas durante 30 días.'
            ],
            [
                'nombre' => 'Bravecto 20-40kg',
                'codigo' => 'MED003',
                'categoria' => 'medicamento',
                'precio' => 580.00,
                'stock' => 18,
                'descripcion' => 'Antiparasitario de larga duración para perros. Protección de 12 semanas contra pulgas y garrapatas.'
            ],
            [
                'nombre' => 'Ivermectina Gotas',
                'codigo' => 'MED004',
                'categoria' => 'medicamento',
                'precio' => 180.00,
                'stock' => 40,
                'descripcion' => 'Antiparasitario interno y externo en gotas orales. Efectivo contra ácaros, nematodos y piojos.'
            ],
            [
                'nombre' => 'Pipeta Frontline Plus',
                'codigo' => 'MED005',
                'categoria' => 'medicamento',
                'precio' => 320.00,
                'stock' => 50,
                'descripcion' => 'Antiparasitario tópico para perros. Elimina pulgas, garrapatas y piojos. Protección por 30 días.'
            ],
            [
                'nombre' => 'Amoxicilina 250mg',
                'codigo' => 'MED006',
                'categoria' => 'medicamento',
                'precio' => 280.00,
                'stock' => 35,
                'descripcion' => 'Antibiótico de amplio espectro. Indicaciones: infecciones respiratorias, urinarias y de piel en mascotas.'
            ],
            [
                'nombre' => 'Metronidazol 250mg',
                'codigo' => 'MED007',
                'categoria' => 'medicamento',
                'precio' => 220.00,
                'stock' => 28,
                'descripcion' => 'Antibiótico y antiprotozoario. Tratamiento de diarrea, giardiasis y infecciones anaerobias.'
            ],
            [
                'nombre' => 'Prednisona 5mg',
                'codigo' => 'MED008',
                'categoria' => 'medicamento',
                'precio' => 195.00,
                'stock' => 22,
                'descripcion' => 'Corticosteroide antiinflamatorio. Usado para alergias, inflamaciones y problemas de piel.'
            ],

            // VACUNAS (5)
            [
                'nombre' => 'Vacuna Séxtuple Canina',
                'codigo' => 'VAC001',
                'categoria' => 'vacuna',
                'precio' => 350.00,
                'stock' => 50,
                'descripcion' => 'Vacuna múltiple para perros: distemper, hepatitis, leptospira, parvovirus, parainfluenza y coronavirus.'
            ],
            [
                'nombre' => 'Vacuna Triple Felina',
                'codigo' => 'VAC002',
                'categoria' => 'vacuna',
                'precio' => 300.00,
                'stock' => 40,
                'descripcion' => 'Vacuna para gatos: rinotraqueitis, calicivirus y panleucopenia. Dosis anual.'
            ],
            [
                'nombre' => 'Vacuna Antirrábica',
                'codigo' => 'VAC003',
                'categoria' => 'vacuna',
                'precio' => 250.00,
                'stock' => 60,
                'descripcion' => 'Vacuna contra la rabia para perros y gatos. Obligatoria por ley. Dosis anual.'
            ],
            [
                'nombre' => 'Vacuna Bordetella',
                'codigo' => 'VAC004',
                'categoria' => 'vacuna',
                'precio' => 280.00,
                'stock' => 30,
                'descripcion' => 'Vacuna contra la tos de las perreras (Bordetella bronchiseptica). Requerida para guarderías y exposiciones.'
            ],
            [
                'nombre' => 'Vacuna Leishmaniasis',
                'codigo' => 'VAC005',
                'categoria' => 'vacuna',
                'precio' => 420.00,
                'stock' => 15,
                'descripcion' => 'Vacuna preventiva contra Leishmaniasis visceral canina. Protocolo de 3 dosis iniciales.'
            ],

            // ALIMENTOS (6)
            [
                'nombre' => 'Pro Plan Adulto Razas Pequeñas',
                'codigo' => 'ALI001',
                'categoria' => 'alimento',
                'precio' => 890.00,
                'stock' => 20,
                'descripcion' => 'Alimento premium para perros adultos de razas pequeñas (hasta 10kg). Bolsa de 7.5kg. Rico en proteínas.'
            ],
            [
                'nombre' => 'Pro Plan Adulto Razas Grandes',
                'codigo' => 'ALI002',
                'categoria' => 'alimento',
                'precio' => 1150.00,
                'stock' => 15,
                'descripcion' => 'Alimento premium para perros adultos de razas grandes (25kg+). Bolsa de 15kg. Cuida articulaciones.'
            ],
            [
                'nombre' => 'Whiskas Adulto Sabor Pollo',
                'codigo' => 'ALI003',
                'categoria' => 'alimento',
                'precio' => 450.00,
                'stock' => 35,
                'descripcion' => 'Alimento para gatos adultos sabor pollo. Bolsa de 10kg. Fórmula equilibrada con taurina.'
            ],
            [
                'nombre' => 'Whiskas Gatitos Salmón',
                'codigo' => 'ALI004',
                'categoria' => 'alimento',
                'precio' => 380.00,
                'stock' => 28,
                'descripcion' => 'Alimento para gatitos de 1 a 12 meses. Sabor salmón. Bolsa de 7kg. Apoyo inmunológico.'
            ],
            [
                'nombre' => 'Alimento para Conejos - Oxbow',
                'codigo' => 'ALI005',
                'categoria' => 'alimento',
                'precio' => 320.00,
                'stock' => 12,
                'descripcion' => 'Pellet premium para conejos adultos. Bolsa de 2kg. Alto en fibra, bajo en calcio.'
            ],
            [
                'nombre' => 'Royal Canin Digestive Sensitive',
                'codigo' => 'ALI006',
                'categoria' => 'alimento',
                'precio' => 1050.00,
                'stock' => 8,
                'descripcion' => 'Alimento terapéutico para perros con digestión sensible. Bolsa de 10kg. Prebióticos naturales.'
            ],

            // ACCESORIOS (4)
            [
                'nombre' => 'Collar Antipulgas Seresto',
                'codigo' => 'ACC001',
                'categoria' => 'accesorio',
                'precio' => 280.00,
                'stock' => 45,
                'descripcion' => 'Collar antipulgas y antigarrapatas de 8 meses de duración. Resistente al agua. Para perros y gatos.'
            ],
            [
                'nombre' => 'Arnés Ergonómico Talla M',
                'codigo' => 'ACC002',
                'categoria' => 'accesorio',
                'precio' => 350.00,
                'stock' => 20,
                'descripcion' => 'Arnés ajustable para perros medianos (15-25kg). Ergonómico, acolchado, con asa de control.'
            ],
            [
                'nombre' => 'Shampoo Antipulgas Canino',
                'codigo' => 'ACC003',
                'categoria' => 'accesorio',
                'precio' => 220.00,
                'stock' => 30,
                'descripcion' => 'Shampoo antipulgas para perros. Fórmula suave con aloe vera. Frasco de 500ml.'
            ],
            [
                'nombre' => 'Transportadora Pequeña',
                'codigo' => 'ACC004',
                'categoria' => 'accesorio',
                'precio' => 650.00,
                'stock' => 10,
                'descripcion' => 'Transportadora para mascotas pequeñas (hasta 5kg). Ventilación lateral, segura para vuelos.'
            ],

            // STOCK BAJO (amarillo - entre 5 y 20)
            [
                'nombre' => 'Clavulanato 500mg',
                'codigo' => 'MED009',
                'categoria' => 'medicamento',
                'precio' => 340.00,
                'stock' => 8,
                'descripcion' => 'Antibiótico combinado amoxicilina + ácido clavulánico. Para infecciones resistentes en perros y gatos. Caja de 10 tabletas.'
            ],

            // ÚLTIMAS UNIDADES (rojo - entre 1 y 4)
            [
                'nombre' => 'Vacuna Moquillo Canino',
                'codigo' => 'VAC006',
                'categoria' => 'vacuna',
                'precio' => 310.00,
                'stock' => 3,
                'descripcion' => 'Vacuna contra el moquillo canino (Distemper). Parte del esquema de vacunación básica para cachorros y adultos.'
            ],

            // AGOTADO (gris - 0 unidades)
            [
                'nombre' => 'Juguete Kong Classic Grande',
                'codigo' => 'ACC005',
                'categoria' => 'accesorio',
                'precio' => 380.00,
                'stock' => 0,
                'descripcion' => 'Juguete de goma natural resistente para perros grandes. Ideal para rellenar con premios. Muy duradero.'
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }

        $this->command->info('✅ ' . count($productos) . ' productos creados exitosamente.');
    }
}
