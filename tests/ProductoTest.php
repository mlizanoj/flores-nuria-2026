<?php

use classes\Oferta;
use classes\Producto;
use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{
    /**
     * Test para verificar que el constructor asigna correctamente las propiedades.
     */
    public function testConstructorAsignaValoresCorrectamente()
    {
        $producto = new Producto(1, "Rosa Roja", 10.0, 50, [], 21, "Flores");

        $this->assertEquals(1, $producto->getIdProducto());
        $this->assertEquals("Rosa Roja", $producto->getNombre());
        $this->assertEquals(10.0, $producto->getPrecio());
        $this->assertEquals(21, $producto->getIva());
    }

    /**
     * Test de lógica: Verifica el cálculo del precio con IVA y SIN oferta.
     */
    public function testPrecioConIvaSinOferta()
    {
        // Precio: 100, IVA: 21% -> Resultado esperado: 121
        $producto = new Producto(1, "Planta", 100.0, 10, [], 21, "Decoración");

        $this->assertEquals(121.0, $producto->getPrecioConIva());
    }

    /**
     * Test de lógica: Verifica el cálculo del precio con una oferta aplicada.
     */
    public function testPrecioConIvaYOferta()
    {
        // Simulamos un objeto Oferta usando un Mock (doble de prueba)
        $ofertaMock = $this->createMock(Oferta::class);
        $ofertaMock->method('getActiva')->willReturn(true);
        $ofertaMock->method('getDescuento')->willReturn(20); // 20% descuento

        // Precio Base: 100
        // Descuento 20% -> 80
        // IVA 21% sobre 80 -> 96.8
        $producto = new Producto(1, "Ramo Especial", 100.0, 5, [$ofertaMock], 21, "Ramos");

        $this->assertEquals(96.8, $producto->getPrecioConIva());
    }

    /**
     * Test de lógica: Si hay varias ofertas, debe aplicar la mayor.
     */
    public function testPrecioAplicaLaMejorOferta()
    {
        $oferta10 = $this->createMock(Oferta::class);
        $oferta10->method('getActiva')->willReturn(true);
        $oferta10->method('getDescuento')->willReturn(10);

        $oferta50 = $this->createMock(Oferta::class);
        $oferta50->method('getActiva')->willReturn(true);
        $oferta50->method('getDescuento')->willReturn(50);

        // Precio 100, Mejor descuento 50% -> 50. Con 21% IVA -> 60.5
        $producto = new Producto(1, "Super Oferta", 100.0, 5, [$oferta10, $oferta50], 21, "Promo");

        $this->assertEquals(60.5, $producto->getPrecioConIva());
    }
}
