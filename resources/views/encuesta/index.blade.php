<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta de Satisfacción - {{ $cliente->nombre }}</title>
    <style>
        :root {
            --primary-color: #4a543d;
            --primary-hover: #3a442d;
            --accent-color: #ffd700;
            --background-light: #f8f9fa;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --text-primary: #333;
            --text-secondary: #666;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 24px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-color);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(0);
        }

        h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 24px;
            text-align: center;
        }

        p {
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        .question {
            margin-bottom: 32px;
            padding: 24px;
            background: var(--background-light);
            border-radius: 12px;
            box-shadow: 0 4px 6px var(--shadow-color);
        }

        .question label {
            display: block;
            margin-bottom: 16px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .stars {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .star {
            cursor: pointer;
            font-size: 24px;
            transition: color 0.2s ease;
        }

        .star:hover {
            color: var(--accent-color);
        }

        .star.selected {
            color: var(--accent-color);
        }

        .comments {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid var(--background-light);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            resize: vertical;
            transition: all 0.2s ease;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 84, 61, 0.1);
        }

        .submit-button {
            display: block;
            width: 100%;
            padding: 16px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 32px;
        }

        .submit-button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px var(--shadow-color);
        }

        .success-message {
            display: none;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 32px;
            text-align: center;
            font-weight: 500;
        }

        .success-message.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 32px;
            }

            h1 {
                font-size: 2rem;
            }

            .stars {
                gap: 8px;
            }
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
