@extends('Plantillas.navmenu')

@section('Contenido')
<style>
body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        background-color: #1a2a49;
        min-height: 100vh;
    }
    
</style>
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh; background-color: #1e2a47;">
    <div style="background-color: #151a28; padding: 30px; border-radius: 10px; width: 90%; max-width: 700px; color: white; box-shadow: 0 0 10px rgba(0,0,0,0.3);">
        <h2 style="display: flex; align-items: center; font-size: 24px; margin-bottom: 20px;">
            🏥 Bienvenidos a Clínica Salud Integral
        </h2>
        <p style="margin-bottom: 15px;">
            En Clínica Salud Integral, tu salud y bienestar son nuestra prioridad. Contamos con un equipo médico altamente capacitado, tecnología de vanguardia y un enfoque humano para brindarte atención integral y de calidad.
        </p>
        <ul style="list-style: none; padding-left: 0; margin-bottom: 15px;">
            <li>🩺 Consultas médicas generales y especializadas</li>
            <li>📅 Agenda tu cita fácilmente desde nuestra web</li>
        </ul>
        <p style="margin-bottom: 25px;">
            Cuidamos de vos y de los que más querés, con calidez, confianza y compromiso.
        </p>
        <a href="AgenCita">
            <button style="padding: 10px 20px; background-color: #92e2d2; border: none; border-radius: 10px; color: black; cursor: pointer;">
                Agendar consulta
            </button>
        </a>
    </div>
</div>
@endsection