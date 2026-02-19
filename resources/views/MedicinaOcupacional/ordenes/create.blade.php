@extends('layouts.app')

@section('styles')
<style>
    /* ========================================
       VARIABLES GLOBALES
    ======================================== */
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --danger: #e74a3b;
        --warning: #f6c23e;
        --info: #36b9cc;
    }

    body {
        background: #f8f9fc;
    }

    /* ========================================
       HEADER PRINCIPAL
    ======================================== */
    .page-header-master {
        background: linear-gradient(135deg, #1a592e 0%, #2d7a4a 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 6px 20px rgba(26, 89, 46, 0.25);
        position: relative;
        overflow: hidden;
    }

    .page-header-master::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .header-title h1 {
        font-size: 26px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .header-subtitle {
        font-size: 13px;
        opacity: 0.95;
    }

    .header-badge {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ========================================
       PERFIL DEL PACIENTE
    ======================================== */
    .patient-profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .patient-profile-header {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .patient-profile-header h6 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .patient-profile-body {
        padding: 25px;
    }

    .patient-avatar-section {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .patient-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        margin-right: 20px;
    }

    .patient-info {
        flex: 1;
    }

    .patient-name {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .patient-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .patient-badge {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #5a5c69;
    }

    .patient-vital-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f8f9fc;
    }

    .vital-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fc;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .vital-item:hover {
        background: #e3e6f0;
        transform: translateY(-2px);
    }

    .vital-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 18px;
    }

    .vital-label {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .vital-value {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
    }

    /* ========================================
       SELECTOR DE PERFILES
    ======================================== */
    .profiles-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
        padding: 25px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .section-title i {
        color: #4e73df;
        margin-right: 10px;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
    }

    .profile-card {
        background: white;
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        border-color: #4e73df;
        box-shadow: 0 8px 25px rgba(78, 115, 223, 0.2);
    }

    .profile-card.active {
        border-color: #1cc88a;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    }

    .profile-icon {
        font-size: 36px;
        margin-bottom: 12px;
    }

    .profile-title {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .profile-count {
        font-size: 11px;
        color: #858796;
    }

    /* ========================================
       CATEGORÍAS DE EXÁMENES
    ======================================== */
    .exams-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .exams-card-header {
        background: white;
        border-bottom: 2px solid #f8f9fc;
        padding: 20px 25px;
        display: flex;
        align-items: center;
    }

    .category-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }

    .category-icon-lab {
        background: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }

    .category-icon-immuno {
        background: rgba(54, 185, 204, 0.1);
        color: #36b9cc;
    }

    .category-icon-urine {
        background: rgba(90, 92, 105, 0.1);
        color: #5a5c69;
    }

    .category-icon-respiratory {
        background: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
    }

    .category-icon-xray {
        background: rgba(246, 194, 62, 0.1);
        color: #f6c23e;
    }

    .category-info h6 {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 3px 0;
    }

    .category-description {
        font-size: 12px;
        color: #858796;
        margin: 0;
    }

    .exams-card-body {
        padding: 20px 25px;
    }

    /* ========================================
       EXAM CARDS SELECCIONABLES
    ======================================== */
    .exams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .exam-card {
        background: #f8f9fc;
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 15px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .exam-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border-color: #4e73df;
    }

    .exam-card.selected {
        background: linear-gradient(135deg, #e8f0fe 0%, #d3e3fd 100%);
        border-color: #4e73df;
        border-width: 3px;
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.25);
    }

    .exam-card.selected::before {
        content: '\f00c';
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        top: 8px;
        right: 8px;
        width: 22px;
        height: 22px;
        background: #4e73df;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        animation: checkPop 0.3s ease;
    }

    @keyframes checkPop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .exam-icon {
        font-size: 28px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .exam-card:hover .exam-icon {
        transform: scale(1.1);
    }

    .exam-card.selected .exam-icon {
        color: #4e73df;
    }

    .exam-name {
        font-size: 12px;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
    }

    .exam-card.selected .exam-name {
        color: #4e73df;
    }

    /* Colores por categoría */
    .exam-icon-lab { color: #4e73df; }
    .exam-icon-immuno { color: #36b9cc; }
    .exam-icon-urine { color: #5a5c69; }
    .exam-icon-respiratory { color: #1cc88a; }
    .exam-icon-cardio { color: #e74a3b; }
    .exam-icon-xray { color: #f6c23e; }

    /* ========================================
       PANEL DE RESUMEN
    ======================================== */
    .summary-panel {
        position: sticky;
        top: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .summary-header {
        background: linear-gradient(135deg, #5a5c69, #373840);
        color: white;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-header h6 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
    }

    .summary-count {
        background: white;
        color: #5a5c69;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
    }

    .summary-body {
        padding: 20px;
        max-height: 500px;
        overflow-y: auto;
    }

    .summary-item {
        background: #f8f9fc;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
    }

    .summary-item i.fa-check-circle {
        color: #4e73df;
        margin-right: 10px;
    }

    .remove-item {
        color: #e74a3b;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .remove-item:hover {
        transform: scale(1.2);
    }

    .summary-empty {
        text-align: center;
        padding: 40px 20px;
        color: #858796;
    }

    .summary-empty i {
        font-size: 48px;
        opacity: 0.3;
        margin-bottom: 15px;
    }

    /* ========================================
       OBSERVACIONES
    ======================================== */
    .observations-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 25px;
    }

    .form-label-custom {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .form-label-custom i {
        color: #4e73df;
        margin-right: 8px;
    }

    .form-control-custom {
        border: 2px solid #e3e6f0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    /* ========================================
       BOTONES DE ACCIÓN
    ======================================== */
    .action-footer {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-action {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn-primary-action {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .btn-secondary-action {
        background: #e3e6f0;
        color: #5a5c69;
    }

    /* ========================================
       SCROLLBAR PERSONALIZADO
    ======================================== */
    .summary-body::-webkit-scrollbar {
        width: 6px;
    }

    .summary-body::-webkit-scrollbar-track {
        background: #f8f9fc;
        border-radius: 10px;
    }

    .summary-body::-webkit-scrollbar-thumb {
        background: #d1d3e2;
        border-radius: 10px;
    }

    /* ========================================
       RESPONSIVE
    ======================================== */
    @media (max-width: 768px) {
        .page-header-master {
            padding: 20px;
        }

        .header-title h1 {
            font-size: 22px;
        }

        .patient-avatar-section {
            flex-direction: column;
            text-align: center;
        }

        .patient-avatar {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .exams-grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        }

        .summary-panel {
            position: static;
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('medicina.ordenes.store') }}" method="POST" id="formOrden">
        @csrf
        <input type="hidden" name="consulta_id" value="{{ $consulta->id }}">
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
        <input type="hidden" name="examenes" id="examenes">

        <!-- HEADER PRINCIPAL -->
        <div class="page-header-master">
            <div class="header-content">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="header-icon mr-3">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <div class="header-title">
                            <h1>Orden de Exámenes Médicos</h1>
                            <p class="header-subtitle mb-0">Selección y Configuración de Estudios Clínicos</p>
                        </div>
                    </div>
                    <div>
                        <span class="header-badge mr-2">
                            <i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        </span>
                        <span class="header-badge">
                            <i class="fas fa-stethoscope mr-1"></i>Consulta #{{ str_pad($consulta->id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERFIL DEL PACIENTE -->
        <div class="patient-profile-card">
            <div class="patient-profile-header">
                <h6><i class="fas fa-user-injured mr-2"></i>Información del Paciente</h6>
                <span style="opacity: 0.9; font-size: 13px;">Código: {{ $paciente->cod_emp }}</span>
            </div>
            <div class="patient-profile-body">
                <div class="patient-avatar-section">
                    <div class="patient-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="patient-info">
                        <div class="patient-name">{{ $paciente->nombre_completo }}</div>
                        <div class="patient-badges">
                            <span class="patient-badge">
                                <i class="fas fa-id-card mr-1"></i>CI: {{ $paciente->ci }}
                            </span>
                            <span class="patient-badge">
                                <i class="fas fa-briefcase mr-1"></i>{{ $paciente->des_cargo }}
                            </span>
                            <span class="patient-badge">
                                <i class="fas fa-building mr-1"></i>{{ $paciente->des_depart }}
                            </span>
                            <span class="patient-badge">
                                <i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años
                            </span>
                        </div>
                    </div>
                </div>

                <div class="patient-vital-grid">
                    <div class="vital-item">
                        <div class="vital-icon" style="background: rgba(231, 74, 59, 0.1); color: #e74a3b;">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="vital-label">Tipo Sangre</div>
                        <div class="vital-value">{{ $paciente->tipo_sangre ?? 'N/D' }}</div>
                    </div>

                    <div class="vital-item">
                        <div class="vital-icon" style="background: rgba(78, 115, 223, 0.1); color: #4e73df;">
                            <i class="fas fa-weight"></i>
                        </div>
                        <div class="vital-label">Peso</div>
                        <div class="vital-value">{{ $paciente->peso_inicial ?? 'N/D' }} kg</div>
                    </div>

                    <div class="vital-item">
                        <div class="vital-icon" style="background: rgba(28, 200, 138, 0.1); color: #1cc88a;">
                            <i class="fas fa-ruler-vertical"></i>
                        </div>
                        <div class="vital-label">Estatura</div>
                        <div class="vital-value">{{ $paciente->estatura ?? 'N/D' }} cm</div>
                    </div>

                    <div class="vital-item">
                        <div class="vital-icon" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="vital-label">IMC</div>
                        <div class="vital-value">
                            @php
                                $peso = $paciente->peso_inicial ?? 0;
                                $estatura = $paciente->estatura ?? 0;
                                $imc = ($peso && $estatura) ? round($peso / (($estatura/100) ** 2), 1) : 'N/D';
                            @endphp
                            {{ $imc }}
                        </div>
                    </div>

                    <div class="vital-item">
                        <div class="vital-icon" style="background: rgba(54, 185, 204, 0.1); color: #36b9cc;">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                        <div class="vital-label">Sexo</div>
                        <div class="vital-value">{{ $paciente->sexo == 'M' ? 'Masculino' : 'Femenino' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SELECTOR DE PERFILES -->
        <div class="profiles-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-magic"></i>Perfiles Rápidos de Exámenes
                </h2>
            </div>
            <div class="profile-grid">
                <div class="profile-card" data-profile="pre_vacacional">
                    <div class="profile-icon">🏖️</div>
                    <div class="profile-title">Pre-Vacacional</div>
                    <div class="profile-count">4 exámenes</div>
                </div>

                <div class="profile-card" data-profile="post_vacacional">
                    <div class="profile-icon">🛬</div>
                    <div class="profile-title">Post-Vacacional</div>
                    <div class="profile-count">5 exámenes</div>
                </div>

                <div class="profile-card" data-profile="pre_empleo">
                    <div class="profile-icon">👔</div>
                    <div class="profile-title">Pre-Empleo</div>
                    <div class="profile-count">8 exámenes</div>
                </div>

                <div class="profile-card" data-profile="respiratorio">
                    <div class="profile-icon">🫁</div>
                    <div class="profile-title">Respiratorio</div>
                    <div class="profile-count">3 exámenes</div>
                </div>

                <div class="profile-card" data-profile="clear">
                    <div class="profile-icon" style="font-size: 28px;">🗑️</div>
                    <div class="profile-title">Limpiar Todo</div>
                    <div class="profile-count">Reiniciar</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- COLUMNA IZQUIERDA: EXÁMENES -->
            <div class="col-lg-8">
                <!-- HEMATOLOGÍA Y BIOQUÍMICA -->
                <div class="exams-card">
                    <div class="exams-card-header">
                        <div class="category-icon category-icon-lab">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="category-info">
                            <h6>Hematología y Bioquímica</h6>
                            <p class="category-description">Exámenes de sangre y química sanguínea</p>
                        </div>
                    </div>
                    <div class="exams-card-body">
                        <div class="exams-grid">
                            <div class="exam-card" data-exam="Hematología Completa">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-vial"></i>
                                </div>
                                <div class="exam-name">Hematología Completa</div>
                            </div>

                            <div class="exam-card" data-exam="Glicemia">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-candy-cane"></i>
                                </div>
                                <div class="exam-name">Glicemia</div>
                            </div>

                            <div class="exam-card" data-exam="Urea">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-vial-circle-check"></i>
                                </div>
                                <div class="exam-name">Urea</div>
                            </div>

                            <div class="exam-card" data-exam="Creatinina">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-vials"></i>
                                </div>
                                <div class="exam-name">Creatinina</div>
                            </div>

                            <div class="exam-card" data-exam="Colesterol Total">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-heart-pulse"></i>
                                </div>
                                <div class="exam-name">Colesterol Total</div>
                            </div>

                            <div class="exam-card" data-exam="Triglicéridos">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-droplet"></i>
                                </div>
                                <div class="exam-name">Triglicéridos</div>
                            </div>

                            <div class="exam-card" data-exam="Transaminasas (TGO/TGP)">
                                <div class="exam-icon exam-icon-lab">
                                    <i class="fas fa-prescription-bottle"></i>
                                </div>
                                <div class="exam-name">Transaminasas (TGO/TGP)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INMUNOLOGÍA Y SEROLOGÍA -->
                <div class="exams-card">
                    <div class="exams-card-header">
                        <div class="category-icon category-icon-immuno">
                            <i class="fas fa-shield-virus"></i>
                        </div>
                        <div class="category-info">
                            <h6>Inmunología y Serología</h6>
                            <p class="category-description">Pruebas inmunológicas y detección viral</p>
                        </div>
                    </div>
                    <div class="exams-card-body">
                        <div class="exams-grid">
                            <div class="exam-card" data-exam="VDRL">
                                <div class="exam-icon exam-icon-immuno">
                                    <i class="fas fa-syringe"></i>
                                </div>
                                <div class="exam-name">VDRL</div>
                            </div>

                            <div class="exam-card" data-exam="HIV">
                                <div class="exam-icon exam-icon-immuno">
                                    <i class="fas fa-virus"></i>
                                </div>
                                <div class="exam-name">HIV</div>
                            </div>

                            <div class="exam-card" data-exam="Tipiaje Sanguíneo">
                                <div class="exam-icon exam-icon-immuno">
                                    <i class="fas fa-tint"></i>
                                </div>
                                <div class="exam-name">Tipiaje Sanguíneo</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- UROANÁLISIS -->
                <div class="exams-card">
                    <div class="exams-card-header">
                        <div class="category-icon category-icon-urine">
                            <i class="fas fa-flask-vial"></i>
                        </div>
                        <div class="category-info">
                            <h6>Uroanálisis y Coprología</h6>
                            <p class="category-description">Análisis de orina y heces</p>
                        </div>
                    </div>
                    <div class="exams-card-body">
                        <div class="exams-grid">
                            <div class="exam-card" data-exam="Examen de Orina">
                                <div class="exam-icon exam-icon-urine">
                                    <i class="fas fa-vial"></i>
                                </div>
                                <div class="exam-name">Examen de Orina</div>
                            </div>

                            <div class="exam-card" data-exam="Examen de Heces">
                                <div class="exam-icon exam-icon-urine">
                                    <i class="fas fa-microscope"></i>
                                </div>
                                <div class="exam-name">Examen de Heces</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ESTUDIOS OCUPACIONALES -->
                <div class="exams-card">
                    <div class="exams-card-header">
                        <div class="category-icon category-icon-respiratory">
                            <i class="fas fa-lungs"></i>
                        </div>
                        <div class="category-info">
                            <h6>Estudios Ocupacionales</h6>
                            <p class="category-description">Evaluaciones funcionales especializadas</p>
                        </div>
                    </div>
                    <div class="exams-card-body">
                        <div class="exams-grid">
                            <div class="exam-card" data-exam="Espirometría">
                                <div class="exam-icon exam-icon-respiratory">
                                    <i class="fas fa-lungs"></i>
                                </div>
                                <div class="exam-name">Espirometría</div>
                            </div>

                            <div class="exam-card" data-exam="Audiometría">
                                <div class="exam-icon exam-icon-respiratory">
                                    <i class="fas fa-ear-listen"></i>
                                </div>
                                <div class="exam-name">Audiometría</div>
                            </div>

                            <div class="exam-card" data-exam="Electrocardiograma">
                                <div class="exam-icon exam-icon-cardio">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <div class="exam-name">Electrocardiograma</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RAYOS X -->
                <div class="exams-card">
                    <div class="exams-card-header">
                        <div class="category-icon category-icon-xray">
                            <i class="fas fa-x-ray"></i>
                        </div>
                        <div class="category-info">
                            <h6>Rayos X e Imagenología</h6>
                            <p class="category-description">Estudios radiológicos</p>
                        </div>
                    </div>
                    <div class="exams-card-body">
                        <div class="exams-grid">
                            <div class="exam-card" data-exam="RX Tórax PA">
                                <div class="exam-icon exam-icon-xray">
                                    <i class="fas fa-x-ray"></i>
                                </div>
                                <div class="exam-name">RX Tórax PA</div>
                            </div>

                            <div class="exam-card" data-exam="RX Columna Lumbosacra">
                                <div class="exam-icon exam-icon-xray">
                                    <i class="fas fa-bone"></i>
                                </div>
                                <div class="exam-name">RX Columna Lumbosacra</div>
                            </div>

                            <div class="exam-card" data-exam="RX Columna Cervical">
                                <div class="exam-icon exam-icon-xray">
                                    <i class="fas fa-bone"></i>
                                </div>
                                <div class="exam-name">RX Columna Cervical</div>
                            </div>

                            <div class="exam-card" data-exam="RX Rodilla">
                                <div class="exam-icon exam-icon-xray">
                                    <i class="fas fa-bone"></i>
                                </div>
                                <div class="exam-name">RX Rodilla</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- OBSERVACIONES -->
                <div class="observations-card">
                    <label class="form-label-custom">
                        <i class="fas fa-comment-medical"></i>Indicaciones Especiales
                    </label>
                    <textarea class="form-control form-control-custom" name="observaciones" rows="3" placeholder="Ejemplo: Ayuno estricto de 12 horas. Traer primera orina de la mañana."></textarea>
                </div>
            </div>

            <!-- COLUMNA DERECHA: RESUMEN -->
            <div class="col-lg-4">
                <div class="summary-panel">
                    <div class="summary-header">
                        <h6>Exámenes Seleccionados</h6>
                        <span class="summary-count">0</span>
                    </div>
                    <div class="summary-body" id="selectedList">
                        <div class="summary-empty">
                            <i class="fas fa-clipboard-list"></i>
                            <p class="mb-0">Selecciona los exámenes<br>que deseas ordenar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER DE ACCIONES -->
        <div class="action-footer">
            <a href="{{ route('medicina.consultas.index') }}" class="btn btn-action btn-secondary-action">
                <i class="fas fa-times mr-2"></i>Omitir por Ahora
            </a>
            <button type="submit" class="btn btn-action btn-primary-action">
                <i class="fas fa-print mr-2"></i>Generar Orden e Imprimir
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let selectedExams = [];

    // Perfiles de exámenes
    const profiles = {
        'pre_vacacional': ['Hematología Completa', 'Glicemia', 'VDRL', 'Examen de Orina'],
        'post_vacacional': ['Hematología Completa', 'Glicemia', 'Colesterol Total', 'Triglicéridos', 'Examen de Orina'],
        'pre_empleo': ['Hematología Completa', 'Glicemia', 'VDRL', 'Examen de Orina', 'Examen de Heces', 'RX Tórax PA', 'Espirometría', 'Audiometría'],
        'respiratorio': ['RX Tórax PA', 'Espirometría', 'Hematología Completa']
    };

    // Función para actualizar la UI
    function updateUI() {
        // Actualizar contador
        $('.summary-count').text(selectedExams.length);

        // Actualizar lista
        const listContainer = $('#selectedList');
        if (selectedExams.length === 0) {
            listContainer.html(`
                <div class="summary-empty">
                    <i class="fas fa-clipboard-list"></i>
                    <p class="mb-0">Selecciona los exámenes<br>que deseas ordenar</p>
                </div>
            `);
        } else {
            let html = '';
            selectedExams.forEach(exam => {
                html += `
                    <div class="summary-item">
                        <span><i class="fas fa-check-circle"></i>${exam}</span>
                        <i class="fas fa-times remove-item" data-exam="${exam}"></i>
                    </div>
                `;
            });
            listContainer.html(html);
        }

        // Actualizar input hidden
        $('#examenes').val(JSON.stringify(selectedExams));

        // Actualizar clases de cards
        $('.exam-card').each(function() {
            const examName = $(this).data('exam');
            if (selectedExams.includes(examName)) {
                $(this).addClass('selected');
            } else {
                $(this).removeClass('selected');
            }
        });
    }

    // Click en exam card
    $(document).on('click', '.exam-card', function() {
        const examName = $(this).data('exam');
        const index = selectedExams.indexOf(examName);

        if (index > -1) {
            selectedExams.splice(index, 1);
        } else {
            selectedExams.push(examName);
        }

        updateUI();
    });

    // Click en remover item
    $(document).on('click', '.remove-item', function(e) {
        e.stopPropagation();
        const examName = $(this).data('exam');
        const index = selectedExams.indexOf(examName);
        if (index > -1) {
            selectedExams.splice(index, 1);
            updateUI();
        }
    });

    // Click en perfiles
    $('.profile-card').click(function() {
        const profile = $(this).data('profile');

        // Remover active de todos
        $('.profile-card').removeClass('active');

        if (profile === 'clear') {
            selectedExams = [];
            Swal.fire({
                icon: 'info',
                title: 'Selección Limpiada',
                text: 'Se han eliminado todos los exámenes seleccionados',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            $(this).addClass('active');
            selectedExams = profiles[profile] || [];
            Swal.fire({
                icon: 'success',
                title: 'Perfil Cargado',
                text: `${selectedExams.length} exámenes seleccionados`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        }

        updateUI();
    });

    // Validación de formulario
    $('#formOrden').submit(function(e) {
        if (selectedExams.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Sin Exámenes Seleccionados',
                text: 'Debe seleccionar al menos un examen para generar la orden',
                confirmButtonColor: '#4e73df'
            });
            return false;
        }
    });
});
</script>

@if(session('print_id'))
<script>
    Swal.fire({
        title: '¡Consulta Guardada Exitosamente!',
        html: '<p>La atención médica ha sido registrada correctamente.</p> <p>Por favor genere la Orden de Examanes.</p> <p class="text-muted small">¿Desea imprimir el récipe ahora?</p>',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: '<i class="fas fa-print"></i> Imprimir Récipe',
        cancelButtonText: '<i class="fas fa-file-medical-alt"></i> Generar Orden de Examenes'
    }).then((result) => {
        if (result.isConfirmed) {
            let timerInterval;
        Swal.fire({
          title: "No olvdide generar la orden de Examenes para esta consulta!",
          html: "Redirigiendo al recipe en <b></b> millisegundos.",
          timer: 5000,
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
              timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
          },
          willClose: () => {
            clearInterval(timerInterval);
          }
        }).then((result) => {
          if (result.dismiss === Swal.DismissReason.timer) {
             window.open("{{ route('medicina.consultas.imprimir', session('print_id')) }}", '_blank');
            console.log("I was closed by the timer");
          }
        });
           
        }
    });
</script>
@endif

@endsection