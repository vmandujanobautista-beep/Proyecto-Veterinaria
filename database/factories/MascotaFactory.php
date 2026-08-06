<?php
namespace Database\Factories;

use App\Models\Mascota;
use Illuminate\Database\Eloquent\Factories\Factory;

class MascotaFactory extends Factory
{
    protected $model = Mascota::class;

    public function definition(): array
    {
        $especies = ['Perro', 'Gato', 'Conejo', 'Aves', 'Otro'];
        
        $razasPerro = ['Labrador', 'Pastor Alemán', 'Chihuahua', 'Golden Retriever', 'Bulldog', 'Poodle', 'Husky', 'Beagle', 'Pitbull', 'Boxer', 'Mestizo'];
        $razasGato = ['Persa', 'Siamés', 'Angora', 'Bengalí', 'Maine Coon', 'Europeo', 'Mestizo'];
        $razasConejo = ['Holland Lop', 'Rex', 'Mini Lop', 'Angora', 'Mestizo'];
        
        $especie = $this->faker->randomElement($especies);
        
        if ($especie === 'Perro') {
            $raza = $this->faker->randomElement($razasPerro);
        } elseif ($especie === 'Gato') {
            $raza = $this->faker->randomElement($razasGato);
        } elseif ($especie === 'Conejo') {
            $raza = $this->faker->randomElement($razasConejo);
        } else {
            $raza = null;
        }

        return [
            'nombre' => $this->faker->firstName(),
            'especie' => $especie,
            'raza' => $raza,
            'sexo' => $this->faker->randomElement(['macho', 'hembra']),
            'peso' => $this->faker->randomFloat(2, 0.5, 60),
            'fecha_nacimiento' => $this->faker->optional(0.7)->date('Y-m-d', '-2 years'),
            'nota_medica' => $this->faker->optional(0.4)->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
