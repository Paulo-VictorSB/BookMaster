@extends('layouts.homeLayout')

<div class="container">
    <section class="filters" id="filters">
        <div class="filterInputs">
            <button>Categoria</button>
            <button>Autores</button>
            <button>Editora</button>
        </div>
        <hr>
        <div class="filterValues">
            <a href="#">Romance</a>
            <a href="#">Romance</a>
            <a href="#">Romance</a>
            <a href="#">Romance</a>
        </div>
    </section>
    <section id="renderBooks" class="renderBooks">

        <div class="order" id="order">
            <div class="orderInputs">
                <button>A-z</button>
                <button>Z-a</button>
                <button>Ano</button>
                <button>Categoria</button>
                <button>Autor</button>
            </div>
        </div>
    </section>
</div>