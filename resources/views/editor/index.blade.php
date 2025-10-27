<!DOCTYPE html>
<html>
<head>
    <title>SS14 Paper Editor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Стили -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <link rel="icon" href="favicon.ico">
    <style>
        #color-btn-text {
            display: flex;
        }

        xy-popover {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .color-btn {
            margin: 0 !important;
        }

        .codex-editor__redactor {
            padding-bottom: 0 !important;
            margin-right: 0 !important;
        }
        .ce-block {
            margin: 0 !important;
            padding: 0 !important;
        }
        .ce-toolbar__plus, .ce-toolbar__settings-btn {
            display: none !important;
        }
        .ce-paragraph {
            font-family: 'Noto Sans', Arial, sans-serif !important;
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
            line-height: 1.2 !important;
        }
        .ce-paragraph[contenteditable="true"] {
            min-height: 20px !important;
            padding-bottom: 0 !important;
            padding-top: 0 !important;
        }

        /* Отступы у редактора */
        .codex-editor {
            margin: 0 !important;
        }

        /* Отступы у блока с контентом */
        .ce-block__content {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 10px !important;
        }

        /* Отступы у тулбара */
        .ce-toolbar__content {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 10px !important;
        }

        /* Отступы у карточки */
        .card-header {
            padding: 1rem;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;

            border: 1px solid #dee2e6;
            border-radius: 14px;

            box-shadow: 0 0 20px rgba(234, 237, 222, 0.1);
        }

        .card-body {
            padding: 1rem;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 0 20px rgba(234, 237, 222, 0.1);
            transition: all 0.3s ease;

            overflow-y: auto;
        }

        /* Paper style */
        .card-body.paper-style {
            background: #eaedde;
        }
        .card-body.paper-style #editorjs .ce-block__content {
            padding: 0 0px !important;
        }

        /* Office Paper style */
        .card-body.office-paper-style {
            background: #ffffff;
            position: relative;
        }
        .card-body.office-paper-style::before {
            content: '';
            position: absolute;
            top: 0;
            left: 35px;
            right: 35px;
            bottom: 0;
            background-image: linear-gradient(#e5e5e5 1px, transparent 1px);
            background-size: 100% 1.2em;
            pointer-events: none;
        }
        .card-body.office-paper-style #editorjs .ce-block__content {
            padding: 0 20px !important;
            line-height: 1.2em !important;
        }
        .card-body.office-paper-style #editorjs .ce-paragraph {
            line-height: 1.2em !important;
            padding-top: 0.1em !important;
        }
        .card-body.office-paper-style #editorjs .ce-block:first-child .ce-block__content {
            background-position: 0 0;
        }

        /* Napkin style */
        .card-body.napkin-style {
            background: #ead4aa;
            width: 270px !important;
            height: 135px !important; /* Половина от ширины */
            margin: 0 auto;
            overflow: auto;
            overflow-x: hidden;
            padding: 10px !important;
            padding-right: 0 !important;
        }
        .card-body.napkin-style #editorjs {
            height: auto !important;
            min-height: auto !important;
        }
        .card-body.napkin-style #editorjs .ce-block__content {
            padding: 0 10px !important;
        }
        .card-body.napkin-style .codex-editor__redactor {
            padding-bottom: 0 !important;
            min-height: auto !important;
        }

        .style-selector {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .style-selector button {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            border: 1px solid #ced4da;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .style-selector button:hover {
            background: #f8f9fa;
        }

        .style-selector button.active {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .tab-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .tab-pane {
            height: 100%;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .tab-pane.active {
            display: flex !important;
            flex-direction: column;
            height: 100%;
            flex: 1;
        }

        #bbcode-editor {
            height: 100% !important;
            resize: none;
            flex: 1;
            background-color: #eaedde;
            min-height: 0; /* Важно для корректной работы flex */
            padding: 10px;
            line-height: 1.4;
            border: none;
            outline: none;
        }

        #editorjs {
            height: 100%;
            flex: 1;
            min-height: 0; /* Важно для корректной работы flex */
        }

        /* Стили для заголовков */
        .header-1 {
            font-size: 2em !important;
            font-weight: bold !important;
            display: inline !important;
            line-height: 0.7 !important;
            font-family: 'Noto Sans', Arial, sans-serif !important;
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
        }

        .header-2 {
            font-size: 1.5em !important;
            font-weight: bold !important;
            display: inline !important;
            line-height: 0.8 !important;
            font-family: 'Noto Sans', Arial, sans-serif !important;
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
        }

        .header-3, .header-4, .header-5 {
            font-size: 1.2em !important;
            font-weight: bold !important;
            display: inline !important;
            line-height: 0.9 !important;
            font-family: 'Noto Sans', Arial, sans-serif !important;
            white-space: pre-wrap !important;
            word-wrap: break-word !important;
        }

        /* Стили для выбора цвета */
        .color-picker-wrapper {
            display: flex;
            padding: 10px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
            gap: 10px;
            z-index: 1000;
        }

        .picker-block {
            position: relative;
        }

        .color-selector {
            position: absolute;
            width: 10px;
            height: 10px;
            border: 2px solid white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            box-shadow: 0 0 2px rgba(0,0,0,0.8);
        }

        .hue-slider {
            cursor: pointer;
        }

        .hue-selector {
            position: absolute;
            right: 10px;
            width: 30px;
            height: 4px;
            background: white;
            border: 1px solid rgba(0,0,0,0.4);
            transform: translateY(-50%);
            pointer-events: none;
        }

        .card {
            width: 100%;
            max-height: 730px;
            min-height: 730px;
            overflow-y: hidden;
            overflow-x: hidden;

            background-color: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none !important;
        }

        @media (min-width: 768px) {
            .card {
                width: 540px;
            }
        }

        /* Стили для центрирования */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #0a0f1d;
            position: relative;
        }

        /* Звёздный фон */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(white, rgba(255,255,255,.2) 2px, transparent 3px),
                radial-gradient(white, rgba(255,255,255,.15) 1px, transparent 2px),
                radial-gradient(white, rgba(255,255,255,.1) 2px, transparent 3px);
            background-size: 550px 550px, 350px 350px, 250px 250px;
            background-position: 0 0, 40px 60px, 130px 270px;
            animation: starsAnimation 200s linear infinite;
        }

        /* Туманности */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba(81, 119, 255, 0.2), transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255, 81, 81, 0.2), transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(128, 0, 255, 0.15), transparent 60%),
                radial-gradient(circle at 30% 80%, rgba(0, 255, 255, 0.1), transparent 50%);
            animation: nebulaAnimation 30s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes starsAnimation {
            from {
                background-position: 0 0, 40px 60px, 130px 270px;
            }
            to {
                background-position: 550px 550px, 390px 410px, 380px 520px;
            }
        }

        @keyframes nebulaAnimation {
            0% {
                transform: scale(1) rotate(0deg);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.2) rotate(5deg);
                opacity: 0.7;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 0.5;
            }
        }

        /* Пульсирующие звёзды */
        .star {
            position: fixed;
            width: 3px;
            height: 3px;
            background: white;
            border-radius: 50%;
            animation: starPulse 3s infinite;
        }

        @keyframes starPulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.5);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .row {
            width: 100%;
            margin: 0;
        }

        /* Космические элементы */
        .space-pen {
            position: absolute;
            width: 40px;
            height: 700px; /* Уменьшаем высоту для места под стержень */
            right: -60px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(90deg,
                #6c757d,
                #495057 20%,
                #6c757d 50%,
                #495057 80%,
                #6c757d
            );
            border-radius: 20px;
            box-shadow:
                inset 5px 0 10px rgba(0,0,0,0.3),
                inset -5px 0 10px rgba(0,0,0,0.3),
                0 0 15px rgba(108, 117, 125, 0.4);
            animation: penFloat 4s ease-in-out infinite;
            z-index: 1000;
        }

        /* Колпачок ручки */
        .space-pen::before {
            content: '';
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 45px;
            background: linear-gradient(90deg, #495057, #6c757d);
            border-radius: 20px 20px 5px 5px;
            box-shadow:
                inset 2px 2px 5px rgba(255,255,255,0.2),
                inset -2px -2px 5px rgba(0,0,0,0.2),
                0 0 10px rgba(0, 0, 0, 0.3);
        }

        /* Язычок колпачка */
        .pen-clip {
            position: absolute;
            top: -30px;
            right: -15px;
            width: 12px;
            height: 35px;
            background: linear-gradient(90deg, #495057, #6c757d);
            border-radius: 6px;
            transform: rotate(-5deg);
            box-shadow:
                inset 1px 1px 2px rgba(255,255,255,0.2),
                inset -1px -1px 2px rgba(0,0,0,0.2),
                2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Стержень ручки */
        .pen-refill {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 60px;
            background: linear-gradient(to bottom,
                #343a40,
                #212529
            );
            border-radius: 10px;
            box-shadow:
                inset 1px 1px 3px rgba(255,255,255,0.1),
                inset -1px -1px 3px rgba(0,0,0,0.3);
        }

        /* Кончик стержня */
        .pen-refill::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 15px;
            background: #000;
            clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
        }

        /* Детали ручки */
        .space-pen-details {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background:
                linear-gradient(90deg,
                    transparent 45%,
                    rgba(255,255,255,0.1) 48%,
                    rgba(255,255,255,0.1) 52%,
                    transparent 55%
                );
        }

        /* Кольца на ручке */
        .space-pen-ring {
            position: absolute;
            width: 100%;
            height: 15px;
            background: linear-gradient(90deg, #495057, #6c757d);
            box-shadow:
                inset 0 2px 5px rgba(255,255,255,0.1),
                inset 0 -2px 5px rgba(0,0,0,0.2);
        }

        .space-pen-ring:nth-child(1) { top: 50px; }
        .space-pen-ring:nth-child(2) { top: 150px; }
        .space-pen-ring:nth-child(3) { bottom: 150px; }
        .space-pen-ring:nth-child(4) { bottom: 50px; }

        @keyframes penFloat {
            0%, 100% {
                transform: translateY(-50%) rotate(1deg);
            }
            50% {
                transform: translateY(-50%) rotate(-1deg);
            }
        }

        /* Планеты */
        .planet {
            position: fixed;
            border-radius: 50%;
            box-shadow: inset -25px -25px 40px rgba(0,0,0,.5);
            animation: planetRotate 30s linear infinite;
        }

        .planet-1 {
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at 30% 30%, #4CAF50, #2E7D32);
            top: 10%;
            left: 5%;
        }

        .planet-2 {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle at 30% 30%, #FF9800, #F57C00);
            bottom: 10%;
            right: 5%;
        }

        .card-wrapper {
            position: relative;
        }

        /* Добавляем свечение вокруг карточки */
        .card {
            box-shadow: 0 0 20px rgba(234, 237, 222, 0.1);
        }

        /* Стили для inline-тулбара и кнопки цвета */
        .ce-inline-toolbar,
        .ce-inline-toolbar * {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .ce-inline-toolbar .ce-inline-toolbar__buttons,
        .ce-inline-toolbar .ce-inline-tool,
        .ce-inline-toolbar .color-section,
        .ce-inline-toolbar xy-popover,
        .ce-inline-toolbar xy-button,
        .ce-inline-toolbar .color-btn {
            height: 34px !important;
            min-height: 34px !important;
            max-height: 34px !important;
            line-height: 34px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .ce-inline-toolbar .ce-inline-tool {
            width: 34px !important;
        }

        /* Принудительно устанавливаем стили для кнопки цвета */
        .ce-inline-toolbar xy-button,
        .ce-inline-toolbar .color-btn,
        .ce-inline-toolbar xy-button[class*="color-btn"],
        .ce-inline-toolbar button[class*="color-btn"] {
            width: 34px !important;
            min-width: 34px !important;
            max-width: 34px !important;
            height: 34px !important;
            min-height: 34px !important;
            max-height: 34px !important;
            line-height: 34px !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 14px !important;
            border: none !important;
            background-clip: padding-box !important;
        }

        /* Стили для текста внутри кнопки */
        .ce-inline-toolbar xy-button *,
        .ce-inline-toolbar .color-btn *,
        .ce-inline-toolbar [class*="color-btn"] * {
            line-height: 34px !important;
            height: 34px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Стили для кнопки выбора цвета */
        .color-section {
            display: flex !important;
            align-items: center !important;
            height: 34px !important;
        }

        xy-popover {
            display: flex !important;
            align-items: center !important;
            height: 34px !important;
        }

        xy-button.color-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 34px !important;
            width: 34px !important;
            padding: 0 !important;
            margin: 0 !important;
            line-height: 34px !important;
        }

        /* Стили для попапа с цветами */
        xy-popcon {
            padding: 5px !important;
        }

        .color-sign {
            display: grid !important;
            grid-template-columns: repeat(10, 1fr) !important;
            gap: 2px !important;
            padding: 5px !important;
        }

        .color-cube {
            width: 20px !important;
            height: 20px !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer !important;
        }

        .rainbow-mask {
            width: 20px !important;
            height: 20px !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer !important;
        }

        /* Сброс стилей для xy-button */
        xy-button,
        xy-button#color-btn,
        xy-button.color-btn,
        xy-button[id="color-btn"],
        xy-button[class*="color-btn"] {
            all: unset !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 34px !important;
            height: 34px !important;
            min-width: 34px !important;
            min-height: 34px !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            outline: none !important;
            background: none !important;
            box-sizing: border-box !important;
            position: static !important;
            transform: none !important;
            font: inherit !important;
            text-align: center !important;
            vertical-align: middle !important;
            line-height: 34px !important;
        }

        /* Сброс для псевдоэлементов */
        xy-button::before,
        xy-button::after,
        xy-button#color-btn::before,
        xy-button#color-btn::after,
        xy-button.color-btn::before,
        xy-button.color-btn::after {
            display: none !important;
            content: none !important;
            width: 0 !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }

        /* Сброс для внутренних элементов */
        xy-button *,
        xy-button#color-btn *,
        xy-button.color-btn * {
            all: unset !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 34px !important;
            height: 34px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Стили для переопределения Shadow DOM */
        xy-color-picker {
            --picker-width: 34px !important;
            --picker-height: 34px !important;
            --button-margin: 0 !important;
            --button-padding: 0 !important;
            width: 34px !important;
            height: 34px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Переопределение стилей для кнопки в Shadow DOM */
        xy-color-picker::part(color-btn),
        xy-color-picker::part(button),
        xy-color-picker .color-btn {
            margin: 0 !important;
            padding: 0 !important;
            width: 34px !important;
            height: 34px !important;
            min-width: 34px !important;
            min-height: 34px !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Переопределение стилей для popover в Shadow DOM */
        xy-color-picker xy-popover,
        xy-color-picker::part(popover) {
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Добавляем шрифт Noto Sans */
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap');

        /* Стили для скроллбара */
        .card-body::-webkit-scrollbar {
            width: 8px;
            background-color: transparent;
        }

        .card-body::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .card-body::-webkit-scrollbar-track {
            background-color: transparent;
            margin: 10px 0;
        }

        /* Стили для Firefox */
        .card-body {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.1) transparent;
        }

        /* Отдельные стили для napkin */
        .card-body.napkin-style::-webkit-scrollbar {
            width: 8px;
            background-color: transparent;
        }

        .card-body.napkin-style::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .card-body.napkin-style::-webkit-scrollbar-track {
            background-color: transparent;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Космические объекты -->
    <div class="planet planet-1"></div>
    <div class="planet planet-2"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>
    <div class="meteor"></div>

    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <div class="card-wrapper">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#editor" role="tab">Editor</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#bbcode" role="tab">Code</a>
                                </li>
                            </ul>
                            <div class="style-selector">
                                <span>Style:</span>
                                <button type="button" class="active" data-style="paper">Paper</button>
                                <button type="button" data-style="office-paper">Office Paper</button>
                                <button type="button" data-style="napkin">Napkin</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="editor" role="tabpanel">
                                    <div id="editorjs"></div>
                                </div>
                                <div class="tab-pane fade" id="bbcode" role="tabpanel">
                                    <textarea id="bbcode-editor" class="form-control" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Скрипты -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.28.2"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/paragraph@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/editorjs-text-color-plugin@2.0.4/dist/bundle.js"></script>
    <script>
        // Создаем кастомный элемент для переопределения стилей
        class CustomColorPicker extends HTMLElement {
            constructor() {
                super();
                this.attachShadow({ mode: 'open' });
            }

            connectedCallback() {
                // Создаем базовый HTML
                this.shadowRoot.innerHTML = `
                    <style>
                        :host {
                            display: inline-flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                        }
                        .color-btn {
                            margin: 0 !important;
                        }
                        xy-popover {
                            padding: 0 !important;
                            margin: 0 !important;
                        }
                    </style>
                    <div class="color-section">
                        <xy-popover id="popover">
                            <button class="color-btn" id="color-btn"></button>
                            <xy-popcon id="popcon">
                                <div class="color-sign" id="colors">
                                    <!-- Цвета будут добавлены динамически -->
                                </div>
                            </xy-popcon>
                        </xy-popover>
                    </div>
                `;

                // Добавляем цвета
                const colors = Object.values(SS14BBCodeParser.colorMap);
                const colorsContainer = this.shadowRoot.querySelector('#colors');
                colors.forEach(color => {
                    const button = document.createElement('button');
                    button.className = 'color-cube';
                    button.style.backgroundColor = color;
                    button.dataset.color = color;
                    button.addEventListener('click', () => this.setColor(color));
                    colorsContainer.appendChild(button);
                });
            }

            setColor(color) {
                const btn = this.shadowRoot.querySelector('#color-btn');
                btn.style.setProperty('--themeColor', color);
                this.dispatchEvent(new CustomEvent('color-change', { detail: { color } }));
            }
        }

        // Регистрируем кастомный элемент
        customElements.define('custom-color-picker', CustomColorPicker);

        // Заменяем стандартный xy-color-picker на наш кастомный
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeName === 'XY-COLOR-PICKER') {
                            const custom = document.createElement('custom-color-picker');
                            node.parentNode.replaceChild(custom, node);
                        }
                    });
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>
    <script>
        // Проверяем загрузку ColorPlugin
        if (typeof ColorPlugin === 'undefined') {
            console.error('ColorPlugin не загружен!');
        }
    </script>
    <script src="{{ asset('js/editor.js?v=1.0.1') }}"></script>
    <script>
        // Добавляем обработчик для кнопок выбора стиля
        document.addEventListener('DOMContentLoaded', () => {
            const styleButtons = document.querySelectorAll('.style-selector button');
            const cardBody = document.querySelector('.card-body');

            // Устанавливаем начальный стиль
            cardBody.classList.add('paper-style');

            styleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Убираем активный класс у всех кнопок
                    styleButtons.forEach(btn => btn.classList.remove('active'));
                    // Добавляем активный класс текущей кнопке
                    button.classList.add('active');

                    // Убираем все стили
                    cardBody.classList.remove('paper-style', 'office-paper-style', 'napkin-style');
                    // Добавляем выбранный стиль
                    cardBody.classList.add(`${button.dataset.style}-style`);
                });
            });
        });
    </script>
</body>
</html>
