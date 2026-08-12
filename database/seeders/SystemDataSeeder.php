<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categorías de Activos
        if (DB::table('categoria_activo')->count() === 0) {
            DB::table('categoria_activo')->insert([
                ['nombre' => 'Herramientas manuales',  'descripcion' => 'Palas, machetes, tijeras de poda, azadones', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Equipos de fumigación',  'descripcion' => 'Bombas de espalda, atomizadores, mangueras', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Equipos de transporte',  'descripcion' => 'Carretillas, mulas, costales', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Infraestructura',        'descripcion' => 'Beneficiadero, bodegas, tanques, galpones', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Equipos eléctricos',     'descripcion' => 'Motobombas, descerezadoras, despulpadoras', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. Insumos iniciales
        if (DB::table('insumo')->count() === 0) {
            DB::table('insumo')->insert([
                ['nombre' => 'Urea 46%',              'tipo' => 'fertilizante', 'unidad' => 'kg', 'stock_actual' => 80.00, 'stock_minimo' => 20.00, 'precio_unidad' => 2500.00, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Triple 15 (N-P-K)',     'tipo' => 'fertilizante', 'unidad' => 'kg', 'stock_actual' => 60.00, 'stock_minimo' => 15.00, 'precio_unidad' => 3200.00, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Roundup (glifosato)',   'tipo' => 'herbicida',    'unidad' => 'L',  'stock_actual' => 12.00, 'stock_minimo' => 5.00,  'precio_unidad' => 18500.00, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Curzate M-8',           'tipo' => 'fungicida',    'unidad' => 'kg', 'stock_actual' => 8.00,  'stock_minimo' => 3.00,  'precio_unidad' => 22000.00, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Lorsban 4E',            'tipo' => 'pesticida',    'unidad' => 'L',  'stock_actual' => 5.00,  'stock_minimo' => 2.00,  'precio_unidad' => 28000.00, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 3. EPP iniciales
        if (DB::table('epp')->count() === 0) {
            DB::table('epp')->insert([
                ['nombre' => 'Botas de caucho T-42',       'descripcion' => 'Botas impermeables para trabajo en campo', 'cantidad_total' => 6, 'stock_disponible' => 5, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Guantes de nitrilo',         'descripcion' => 'Guantes desechables para manejo de agroquímicos', 'cantidad_total' => 20, 'stock_disponible' => 18, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Mascarilla respiradora N95', 'descripcion' => 'Mascarilla para fumigación y polvo', 'cantidad_total' => 10, 'stock_disponible' => 8, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Overol de trabajo azul',     'descripcion' => 'Overol de manga larga talla L', 'cantidad_total' => 5, 'stock_disponible' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Gafas de seguridad',         'descripcion' => 'Protección ocular para fumigación', 'cantidad_total' => 8, 'stock_disponible' => 7, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 4. Herramientas
        if (DB::table('herramienta')->count() === 0) {
            DB::table('herramienta')->insert([
                ['nombre' => 'Machete largo 24"',     'descripcion' => 'Machete para limpieza de maleza',     'estado' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Pala de punta',         'descripcion' => 'Pala metálica para siembra',           'estado' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Tijeras de poda',       'descripcion' => 'Tijeras profesionales para poda café', 'estado' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Bomba de espalda 20L',  'descripcion' => 'Bomba manual para fumigación',         'estado' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Carretilla metálica',   'descripcion' => 'Carretilla para transporte de insumos','estado' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Azadón de plateo',      'descripcion' => 'Azadón para plateo y aporque',         'estado' => 'disponible', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 5. Proveedores
        if (DB::table('proveedor')->count() === 0) {
            DB::table('proveedor')->insert([
                ['nombre' => 'Agro La Cosecha Ltda.',      'nit' => '890123456-1', 'telefono' => '3205678901', 'correo' => 'ventas@agrolacosecha.com', 'producto_servicio' => 'Fertilizantes, pesticidas y fungicidas', 'estado' => 'activo', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Ferretería El Constructor',  'nit' => '900234567-2', 'telefono' => '3214567890', 'correo' => 'info@elconstructor.com', 'producto_servicio' => 'Herramientas manuales y equipos agrícolas', 'estado' => 'activo', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Almacén El Caficultor',      'nit' => '800345678-3', 'telefono' => '3185678901', 'correo' => 'compras@elcaficultor.com', 'producto_servicio' => 'EPP, dotación y equipos de protección', 'estado' => 'activo', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 6. Certificaciones
        if (DB::table('certificacion')->count() === 0) {
            DB::table('certificacion')->insert([
                ['nombre' => 'Buenas Prácticas Agrícolas (BPA)', 'entidad_certif' => 'ICA - Instituto Colombiano Agropecuario', 'fecha_expedicion' => '2024-03-15', 'fecha_vencimiento' => '2026-03-15', 'estado' => 'vigente', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Registro caficultor activo', 'entidad_certif' => 'Federación Nacional de Cafeteros de Colombia', 'fecha_expedicion' => '2024-01-10', 'fecha_vencimiento' => '2025-12-31', 'estado' => 'vigente', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
