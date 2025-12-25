@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Acerca de:') }}</h1>

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-profile-image mt-4">
                    <img src="{{ asset('img/favicon.png') }}" class="rounded-circle" alt="user-image">
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="font-weight-bold">GB-Suite</h5>
                            <p>Plataforma integral desarrollada para Granja Boraure C.A</p>
                            
                            <p>⭐ GB‑Suite es una plataforma integral diseñada para optimizar y centralizar los procesos operativos, administrativos y productivos de Granja Boraure. ⭐</p>
                            <a href="https://github.com/luisrojas69" target="_blank" class="btn btn-github">
                                <i class="fab fa-github fa-fw"></i> Ir al repositorio
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-12">
                            <h5 class="font-weight-bold">Cr&eacute;ditos</h5>
                            <p>Esta aplicación fue desarrollada por el <strong>Ing. Luis Rojas</strong>.
</p>
                            <ul>
                                <li><a href="https://laravel.com" target="_blank">Laravel</a> - Open source framework.</li>
                                <li><a href="https://github.com/DevMarketer/LaravelEasyNav" target="_blank">LaravelEasyNav</a> - Making managing navigation in Laravel easy.</li>
                                <li><a href="https://startbootstrap.com/themes/sb-admin-2" target="_blank">SB Admin 2</a> - Thanks to Start Bootstrap.</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

@endsection
