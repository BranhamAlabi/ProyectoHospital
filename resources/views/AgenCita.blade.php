@extends('Plantillas.navmenu')

@section('Contenido')
<style>
 * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #2f3b5c;
      color: white;
    }

    /* NAVBAR */
    /* CONTENIDO */
    .container {
      /* espacio debajo del navbar */
      display: flex;
      justify-content: center;
      align-items: center;
      
    }

    .form-box {
      background-color: #1e2230;
      padding: 30px;
      border-radius: 15px;
      width: 100%;
      max-width: 600px;
    }

    .form-box h2 {
      background-color: #161b28;
      padding: 15px;
      text-align: center;
      border-radius: 10px;
      margin-bottom: 25px;
      font-size: 24px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      font-size: 16px;
    }

    select,
    textarea {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background-color: #dcdcdc;
      color: #333;
      font-size: 14px;
    }

    .calendar-img {
      width: 100%;
      border-radius: 10px;
      margin: 10px 0 20px;
    }

    .btn {
      background-color: #4a81d4;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      float: right;
    }

    .btn:hover {
      background-color: #3a6fb3;
    }
  </style>
<br><br>
<div class="container">
    <div class="form-box">
      <h2>Registro de cita medica</h2>
      <form>
        <label for="paciente">Paciente:</label>
        <select id="paciente" name="paciente">
          <option>Nombre del paciente registrado</option>
        </select>

        <label for="fecha">Fecha:</label>
        <img src="https://i.imgur.com/9yUJZPn.png" alt="Calendario" class="calendar-img">

        <label for="nota">Nota:</label>
        <textarea id="nota" name="nota" rows="5" placeholder="Escriba una nota..."></textarea>

        <button type="submit" class="btn">Registrar</button>
      </form>
    </div>
  </div>
  @endsection


