<?php
namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        $nombres = [
            'Juan', 'María', 'Carlos', 'Ana', 'Pedro', 'Laura', 'Miguel', 
            'Sofía', 'Roberto', 'Elena', 'Jorge', 'Carmen', 'Luis', 'Patricia',
            'Fernando', 'Gabriela', 'Alejandro', 'Daniela', 'Ricardo', 'Valentina',
            'Diego', 'Isabella', 'Sebastián', 'Camila', 'Andrés', 'Luciana',
            'Mateo', 'Emilia', 'Santiago', 'Martina', 'Tomás', 'Renata',
            'Javier', 'Victoria', 'Eduardo', 'Natalia', 'Gustavo', 'Paula'
        ];
        
        $apellidos = [
            'García', 'Rodríguez', 'Martínez', 'López', 'González', 'Hernández',
            'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera',
            'Gómez', 'Díaz', 'Cruz', 'Morales', 'Ortiz', 'Gutiérrez',
            'Chávez', 'Reyes', 'Mendoza', 'Aguilar', 'Vázquez', 'Castillo',
            'Romero', 'Jiménez', 'Ruiz', 'Álvarez', 'Delgado', 'Molina'
        ];

        $codigoPais = $this->faker->randomElement(['+52', '+57', '+58', '+34', '+1', '+54', '+56', '+51']);

        return [
            'nombre' => $this->faker->randomElement($nombres),
            'apellido_paterno' => $this->faker->randomElement($apellidos),
            'apellido_materno' => $this->faker->randomElement($apellidos),
            'apellido' => $this->faker->randomElement($apellidos) . ' ' . $this->faker->randomElement($apellidos),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->numerify('##########'),
            'codigo_pais' => $codigoPais,
            'direccion' => $this->faker->optional(0.7)->address(),
            'codigo_postal' => $this->faker->optional(0.6)->postcode(),
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
