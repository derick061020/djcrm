<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - {{ $cliente->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .question {
            margin-bottom: 20px;
        }
        .question label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .stars {
            display: flex;
            gap: 10px;
        }
        .star {
            cursor: pointer;
            color: #ccc;
        }
        .star.selected {
            color: #ffd700;
        }
        .comments {
            margin-top: 20px;
        }
        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .submit-button:hover {
            background-color: #45a049;
        }
        .success-message {
            display: none;
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Encuesta de Satisfacción</h1>
        <p>Gracias por su tiempo. Por favor, complete la siguiente encuesta para ayudarnos a mejorar nuestros servicios.</p>

        <form id="surveyForm" method="POST" action="{{ route('encuesta.store', $cliente->id) }}">
            @csrf
            
            <div class="question">
                <label for="overall_satisfaction">1. Satisfacción General con el Servicio</label>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-rating="{{ $i }}">⭐</span>
                    @endfor
                </div>
                <input type="hidden" name="overall_satisfaction" id="overall_satisfaction" value="{{ $cliente->overall_satisfaction ?? '' }}">
            </div>

            <div class="question">
                <label for="service_quality">2. Calidad del Servicio</label>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-rating="{{ $i }}">⭐</span>
                    @endfor
                </div>
                <input type="hidden" name="service_quality" id="service_quality" value="{{ $cliente->service_quality ?? '' }}">
            </div>

            <div class="question">
                <label for="product_quality">3. Calidad del Producto</label>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" data-rating="{{ $i }}">⭐</span>
                    @endfor
                </div>
                <input type="hidden" name="product_quality" id="product_quality" value="{{ $cliente->product_quality ?? '' }}">
            </div>

            <div class="question">
                <label for="would_recommend">4. ¿Recomendaría nuestros servicios?</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="would_recommend" value="1" {{ $cliente->would_recommend ? 'checked' : '' }}>
                        <span class="ml-2">Sí</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="would_recommend" value="0" {{ !$cliente->would_recommend ? 'checked' : '' }}>
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <div class="question comments">
                <label for="survey_comments">5. Comentarios Adicionales</label>
                <textarea name="survey_comments" id="survey_comments" rows="4" class="w-full p-2 border rounded" placeholder="Escriba sus comentarios aquí...">{{ $cliente->survey_comments ?? '' }}</textarea>
            </div>

            <button type="submit" class="submit-button">Enviar Encuesta</button>
        </form>

        @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const successMessage = document.querySelector('.success-message');

            // Mostrar estrellas seleccionadas
            stars.forEach(star => {
                const rating = parseInt(star.getAttribute('data-rating'));
                const question = star.closest('.question');
                const input = question.querySelector('input[type="hidden"]');
                const currentRating = parseInt(input.value);
                
                if (currentRating >= rating) {
                    star.classList.add('selected');
                }
            });

            // Manejar clic en estrellas
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    const question = this.closest('.question');
                    const input = question.querySelector('input[type="hidden"]');
                    
                    // Limpiar estrellas
                    question.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
                    
                    // Seleccionar estrellas hasta el valor clickeado
                    for (let i = 1; i <= rating; i++) {
                        question.querySelector(`.star[data-rating="${i}"]`).classList.add('selected');
                    }
                    
                    input.value = rating;
                });
            });

            // Mostrar mensaje de éxito
            if (successMessage) {
                successMessage.style.display = 'block';
            }
        });
    </script>
</body>
</html>
