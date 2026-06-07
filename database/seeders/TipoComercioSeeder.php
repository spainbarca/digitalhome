<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoComercioSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Supermercado',            'icono' => 'local_grocery_store',     'descripcion' => 'Cadena o autoservicio de productos variados.'],
            ['nombre' => 'Hipermercado',            'icono' => 'shopping_cart',           'descripcion' => 'Gran superficie con amplia variedad de productos.'],
            ['nombre' => 'Tienda por departamento', 'icono' => 'store',                   'descripcion' => 'Retail grande con múltiples líneas de productos.'],
            ['nombre' => 'Centro comercial',        'icono' => 'local_mall',              'descripcion' => 'Mall o galería con varias tiendas.'],
            ['nombre' => 'Minimarket',              'icono' => 'local_convenience_store', 'descripcion' => 'Tienda de conveniencia de horario extendido.'],
            ['nombre' => 'Bodega',                  'icono' => 'storefront',              'descripcion' => 'Tienda de barrio de venta al detalle.'],
            ['nombre' => 'Mercado de abastos',      'icono' => 'shopping_basket',         'descripcion' => 'Mercado o puesto de venta de productos frescos.'],
            ['nombre' => 'Farmacia / Botica',       'icono' => 'local_pharmacy',          'descripcion' => 'Venta de medicamentos y productos de salud.'],
            ['nombre' => 'Ferretería',              'icono' => 'hardware',                'descripcion' => 'Materiales, herramientas y artículos de construcción.'],
            ['nombre' => 'Librería / Papelería',    'icono' => 'menu_book',               'descripcion' => 'Libros, útiles escolares y de oficina.'],
            ['nombre' => 'Panadería',               'icono' => 'bakery_dining',           'descripcion' => 'Pan, pasteles y productos de panadería.'],
            ['nombre' => 'Restaurante',             'icono' => 'restaurant',              'descripcion' => 'Servicio de comidas y bebidas.'],
            ['nombre' => 'Cafetería',               'icono' => 'local_cafe',              'descripcion' => 'Café, bebidas y snacks.'],
            ['nombre' => 'Tienda de ropa',          'icono' => 'checkroom',               'descripcion' => 'Prendas de vestir y calzado.'],
            ['nombre' => 'Juguetería',              'icono' => 'toys',                    'descripcion' => 'Juguetes y artículos para niños.'],
            ['nombre' => 'Tienda de mascotas',      'icono' => 'pets',                    'descripcion' => 'Alimento y accesorios para mascotas.'],
            ['nombre' => 'Óptica',                  'icono' => 'eyeglasses',              'descripcion' => 'Lentes y productos para la visión.'],
            ['nombre' => 'Joyería',                 'icono' => 'diamond',                 'descripcion' => 'Joyas, relojes y accesorios.'],
            ['nombre' => 'Electrónica',             'icono' => 'devices',                'descripcion' => 'Equipos electrónicos y tecnología.'],
            ['nombre' => 'Mueblería',               'icono' => 'weekend',                'descripcion' => 'Muebles y artículos para el hogar.'],
            ['nombre' => 'Tienda de deportes',      'icono' => 'sports_soccer',          'descripcion' => 'Artículos y ropa deportiva.'],
            ['nombre' => 'Floristería',             'icono' => 'local_florist',          'descripcion' => 'Flores, arreglos y plantas.'],
            ['nombre' => 'Licorería',               'icono' => 'liquor',                 'descripcion' => 'Bebidas alcohólicas.'],
            ['nombre' => 'Grifo / Estación de servicio', 'icono' => 'local_gas_station', 'descripcion' => 'Combustible y tienda de conveniencia.'],
            ['nombre' => 'Distribuidora / Mayorista', 'icono' => 'warehouse',           'descripcion' => 'Venta al por mayor.'],
            ['nombre' => 'Online',                  'icono' => 'language',               'descripcion' => 'Comercio electrónico / tienda virtual.'],
            ['nombre' => 'Otro',                    'icono' => 'more_horiz',             'descripcion' => 'Cualquier otro tipo de establecimiento.'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_comercio')->insert([
                'id'          => Str::uuid(),
                'nombre'      => $tipo['nombre'],
                'icono'       => $tipo['icono'],
                'descripcion' => $tipo['descripcion'],
                'activo'      => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
