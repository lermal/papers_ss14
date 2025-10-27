// Определяем парсер BB-кода
class SS14BBCodeParser {
    // Карта HTML цветов по категориям
    static colorMap = {
        // Red Colors
        'indianred': '#CD5C5C',
        'lightcoral': '#F08080',
        'salmon': '#FA8072',
        'darksalmon': '#E9967A',
        'lightsalmon': '#FFA07A',
        'crimson': '#DC143C',
        'red': '#FF0000',
        'firebrick': '#B22222',
        'darkred': '#8B0000',

        // Pink Colors
        'pink': '#FFC0CB',
        'lightpink': '#FFB6C1',
        'hotpink': '#FF69B4',
        'deeppink': '#FF1493',
        'mediumvioletred': '#C71585',
        'palevioletred': '#DB7093',

        // Orange Colors
        'coral': '#FF7F50',
        'tomato': '#FF6347',
        'orangered': '#FF4500',
        'darkorange': '#FF8C00',
        'orange': '#FFA500',

        // Yellow Colors
        'gold': '#FFD700',
        'yellow': '#FFFF00',
        'lightyellow': '#FFFFE0',
        'lemonchiffon': '#FFFACD',
        'lightgoldenrodyellow': '#FAFAD2',
        'papayawhip': '#FFEFD5',
        'moccasin': '#FFE4B5',
        'peachpuff': '#FFDAB9',
        'palegoldenrod': '#EEE8AA',
        'khaki': '#F0E68C',
        'darkkhaki': '#BDB76B',

        // Purple Colors
        'lavender': '#E6E6FA',
        'thistle': '#D8BFD8',
        'plum': '#DDA0DD',
        'violet': '#EE82EE',
        'orchid': '#DA70D6',
        'fuchsia': '#FF00FF',
        'magenta': '#FF00FF',
        'mediumorchid': '#BA55D3',
        'mediumpurple': '#9370DB',
        'rebeccapurple': '#663399',
        'blueviolet': '#8A2BE2',
        'darkviolet': '#9400D3',
        'darkorchid': '#9932CC',
        'darkmagenta': '#8B008B',
        'purple': '#800080',
        'indigo': '#4B0082',
        'slateblue': '#6A5ACD',
        'darkslateblue': '#483D8B',
        'mediumslateblue': '#7B68EE',

        // Green Colors
        'greenyellow': '#ADFF2F',
        'chartreuse': '#7FFF00',
        'lawngreen': '#7CFC00',
        'lime': '#00FF00',
        'limegreen': '#32CD32',
        'palegreen': '#98FB98',
        'lightgreen': '#90EE90',
        'mediumspringgreen': '#00FA9A',
        'springgreen': '#00FF7F',
        'mediumseagreen': '#3CB371',
        'seagreen': '#2E8B57',
        'forestgreen': '#228B22',
        'green': '#008000',
        'darkgreen': '#006400',
        'yellowgreen': '#9ACD32',
        'olivedrab': '#6B8E23',
        'olive': '#808000',
        'darkolivegreen': '#556B2F',
        'mediumaquamarine': '#66CDAA',
        'darkseagreen': '#8FBC8B',
        'lightseagreen': '#20B2AA',
        'darkcyan': '#008B8B',
        'teal': '#008080',

        // Blue Colors
        'aqua': '#00FFFF',
        'cyan': '#00FFFF',
        'lightcyan': '#E0FFFF',
        'paleturquoise': '#AFEEEE',
        'aquamarine': '#7FFFD4',
        'turquoise': '#40E0D0',
        'mediumturquoise': '#48D1CC',
        'darkturquoise': '#00CED1',
        'cadetblue': '#5F9EA0',
        'steelblue': '#4682B4',
        'lightsteelblue': '#B0C4DE',
        'powderblue': '#B0E0E6',
        'lightblue': '#ADD8E6',
        'skyblue': '#87CEEB',
        'lightskyblue': '#87CEFA',
        'deepskyblue': '#00BFFF',
        'dodgerblue': '#1E90FF',
        'cornflowerblue': '#6495ED',
        'royalblue': '#4169E1',
        'blue': '#0000FF',
        'mediumblue': '#0000CD',
        'darkblue': '#00008B',
        'navy': '#000080',
        'midnightblue': '#191970',

        // Brown Colors
        'cornsilk': '#FFF8DC',
        'blanchedalmond': '#FFEBCD',
        'bisque': '#FFE4C4',
        'navajowhite': '#FFDEAD',
        'wheat': '#F5DEB3',
        'burlywood': '#DEB887',
        'tan': '#D2B48C',
        'rosybrown': '#BC8F8F',
        'sandybrown': '#F4A460',
        'goldenrod': '#DAA520',
        'darkgoldenrod': '#B8860B',
        'peru': '#CD853F',
        'chocolate': '#D2691E',
        'saddlebrown': '#8B4513',
        'sienna': '#A0522D',
        'brown': '#A52A2A',
        'maroon': '#800000',

        // White Colors
        'white': '#FFFFFF',
        'snow': '#FFFAFA',
        'honeydew': '#F0FFF0',
        'mintcream': '#F5FFFA',
        'azure': '#F0FFFF',
        'aliceblue': '#F0F8FF',
        'ghostwhite': '#F8F8FF',
        'whitesmoke': '#F5F5F5',
        'seashell': '#FFF5EE',
        'beige': '#F5F5DC',
        'oldlace': '#FDF5E6',
        'floralwhite': '#FFFAF0',
        'ivory': '#FFFFF0',
        'antiquewhite': '#FAEBD7',
        'linen': '#FAF0E6',
        'lavenderblush': '#FFF0F5',
        'mistyrose': '#FFE4E1',

        // Gray Colors
        'gainsboro': '#DCDCDC',
        'lightgray': '#D3D3D3',
        'silver': '#C0C0C0',
        'darkgray': '#A9A9A9',
        'gray': '#808080',
        'dimgray': '#696969',
        'lightslategray': '#778899',
        'slategray': '#708090',
        'darkslategray': '#2F4F4F',
        'black': '#000000'
    };

    static toHTML(bbcode) {
        let result = bbcode;

        // Обрабатываем экранированные скобки
        result = result.replace(/\\\[/g, '&#91;').replace(/\\\]/g, '&#93;');

        // Обработка цветов
        const processColors = (text) => {
            const stack = [];
            let currentPos = 0;
            let output = '';

            // Находим все теги цвета
            const colorRegex = /\[color=([a-zA-Z]+|#[0-9A-Fa-f]{6})\]|\[\/\s*color\s*\]/g;
            let match;

            while ((match = colorRegex.exec(text)) !== null) {
                // Добавляем текст до тега
                output += text.slice(currentPos, match.index);

                if (match[0].match(/\[\/\s*color\s*\]/)) {
                    // Закрывающий тег закрывает все открытые теги цвета
                    while (stack.length > 0) {
                        stack.pop();
                        output += '</font>';
                    }
                } else {
                    // Открывающий тег
                    const color = match[1];
                    const hexColor = color.startsWith('#') ? color : this.colorMap[color.toLowerCase()] || '#000000';
                    stack.push(hexColor);
                    output += `<font style="color: ${hexColor}">`;
                }

                currentPos = match.index + match[0].length;
            }

            // Добавляем оставшийся текст
            output += text.slice(currentPos);

            // Закрываем все оставшиеся открытые теги
            while (stack.length > 0) {
                stack.pop();
                output += '</font>';
            }

            return output;
        };

        // Обрабатываем цвета
        result = processColors(result);

        // Обработка других тегов
        result = result
            .replace(/\[bolditalic\](.*?)(?:\[\/\s*bolditalic\s*\]|$)/gs, '<b><i>$1</i></b>')
            .replace(/\[bold\](.*?)(?:\[\/\s*bold\s*\]|$)/gs, '<b>$1</b>')
            .replace(/\[italic\](.*?)(?:\[\/\s*italic\s*\]|$)/gs, '<i>$1</i>')
            .replace(/\[(?:header|head)=([1-5])\](.*?)(?:\[\/\s*(?:header|head)\s*\]|$)/gs, '<span class="header-$1">$2</span>')
            .replace(/\[bullet\/?\]/g, '•');

        // Обрабатываем специальные символы
        result = result
            .replace(/◥/g, '&#9701;')
            .replace(/◣/g, '&#9699;')
            .replace(/‾/g, '&#175;');

        return result;
    }

    static toBBCode(html) {
        let result = html;

        // Заменяем HTML теги на переносы
        result = result
            .replace(/<br\s*\/?>/g, '\n')
            .replace(/<\/p>/g, '\n')
            .replace(/<\/div>/g, '\n');

        // Заменяем &nbsp; на обычные пробелы
        result = result.replace(/&nbsp;/g, ' ');

        // Обработка цветов (делаем до других тегов)
        const processColors = (text) => {
            const stack = [];
            let currentPos = 0;
            let output = '';

            // Находим все font теги с цветом
            const colorRegex = /<font[^>]*?style="color:\s*(?:rgb\((\d+),\s*(\d+),\s*(\d+)\)|#([0-9A-Fa-f]{6}))[^"]*?"[^>]*>|<\/font>/g;
            let match;

            while ((match = colorRegex.exec(text)) !== null) {
                // Добавляем текст до тега
                output += text.slice(currentPos, match.index);

                if (match[0] === '</font>') {
                    // Закрывающий тег
                    if (stack.length > 0) {
                        stack.pop();
                        output += '[/color]';
                    }
                } else {
                    // Открывающий тег
                    let color;
                    if (match[1] !== undefined) {
                        // RGB цвет
                        color = this.rgbToHex(`rgb(${match[1]}, ${match[2]}, ${match[3]})`);
                    } else {
                        // HEX цвет
                        color = '#' + match[4].toUpperCase();
                    }
                    stack.push(color);
                    output += `[color=${color}]`;
                }

                currentPos = match.index + match[0].length;
            }

            // Добавляем оставшийся текст
            output += text.slice(currentPos);

            return output;
        };

        // Обрабатываем цвета
        result = processColors(result);

        // Обработка тегов (без добавления переносов)
        result = result
            .replace(/<b><i>(.*?)<\/i><\/b>/gs, '[bolditalic]$1[/bolditalic]')
            .replace(/<i><b>(.*?)<\/b><\/i>/gs, '[bolditalic]$1[/bolditalic]')
            .replace(/<b>(.*?)<\/b>/gs, '[bold]$1[/bold]')
            .replace(/<strong>(.*?)<\/strong>/gs, '[bold]$1[/bold]')
            .replace(/<i>(.*?)<\/i>/gs, '[italic]$1[/italic]')
            .replace(/<em>(.*?)<\/em>/gs, '[italic]$1[/italic]')
            .replace(/<span class="header-([1-5])">(.*?)<\/span>/gs, '[head=$1]$2[/head]')
            .replace(/•/g, '[bullet/]');

        // Удаляем оставшиеся HTML теги
        result = result.replace(/<[^>]+>/g, '');

        // Нормализуем переносы строк:
        // 1. Заменяем множественные переносы на двойные
        result = result.replace(/\n{3,}/g, '\n\n');

        // 2. Убираем пробелы в начале и конце каждой строки, сохраняя пустые строки
        result = result
            .split('\n')
            .map(line => line.trim())
            .join('\n');

        // 3. Убираем пробелы в начале и конце всего текста
        return result.trim();
    }

    static rgbToHex(rgb) {
        // Проверяем формат rgb(r, g, b)
        const match = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        if (match) {
            const [_, r, g, b] = match;
            return '#' + [r, g, b].map(x => {
                const hex = parseInt(x).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            }).join('').toUpperCase();
        }
        return null;
    }
}

// Добавляем плагин для заголовков
class HeaderTool {
    static get isInline() {
        return true;
    }

    constructor({api}) {
        this.api = api;
        this.button = null;
        this._state = false;
        this.tag = 'SPAN';
        this.class = null;
    }

    render() {
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.innerHTML = 'H';
        this.button.classList.add(this.api.styles.inlineToolButton);

        return this.button;
    }

    surround(range) {
        if (!range) return;

        const termWrapper = this.api.selection.findParentTag(this.tag);

        if (termWrapper) {
            // Если уже есть заголовок, удаляем его
            this.unwrap(termWrapper);
        } else {
            // Показываем меню выбора заголовка
            this.showMenu(range);
        }
    }

    showMenu(range) {
        // Удаляем старое меню, если оно есть
        const oldMenu = document.querySelector('.header-menu');
        if (oldMenu) {
            oldMenu.remove();
            return;
        }

        // Создаем меню
        const menu = document.createElement('div');
        menu.className = 'header-menu';
        menu.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px;
            z-index: 1000;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
        `;

        // Добавляем варианты заголовков
        for (let i = 1; i <= 5; i++) {
            const option = document.createElement('div');
            option.textContent = `H${i}`;
            option.style.cssText = `
                padding: 5px 10px;
                cursor: pointer;
                font-size: ${1.5 - (i-1)*0.1}em;
                font-weight: bold;
            `;

            option.addEventListener('mouseover', () => option.style.backgroundColor = '#f0f0f0');
            option.addEventListener('mouseout', () => option.style.backgroundColor = 'transparent');

            option.addEventListener('click', () => {
                this.class = `header-${i}`;
                this.wrap(range);
                menu.remove();
            });

            menu.appendChild(option);
        }

        // Позиционируем меню под кнопкой
        const buttonRect = this.button.getBoundingClientRect();
        menu.style.top = buttonRect.bottom + window.scrollY + 5 + 'px';
        menu.style.left = buttonRect.left + window.scrollX + 'px';

        // Добавляем меню в документ
        document.body.appendChild(menu);

        // Закрываем меню при клике вне
        document.addEventListener('click', (e) => {
            if (!menu.contains(e.target) && e.target !== this.button) {
                menu.remove();
            }
        }, { once: true });
    }

    wrap(range) {
        if (!this.class || !range) return;

        // Создаем элемент заголовка
        const span = document.createElement(this.tag);
        span.classList.add(this.class);

        // Клонируем диапазон для сохранения
        const clonedRange = range.cloneRange();

        // Извлекаем содержимое и оборачиваем его
        const content = range.extractContents();
        span.appendChild(content);
        range.insertNode(span);

        // Обновляем состояние
        this._state = true;

        // Восстанавливаем выделение
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(clonedRange);

        // Обновляем содержимое через API
        const currentBlockIndex = this.api.blocks.getCurrentBlockIndex();
        const block = this.api.blocks.getBlockByIndex(currentBlockIndex);

        if (block && block.holder) {
            const paragraph = block.holder.querySelector('.ce-paragraph');
            if (paragraph) {
                // Создаем событие input для обновления содержимого
                const event = new Event('input', { bubbles: true });
                paragraph.dispatchEvent(event);
            }
        }
    }

    unwrap(termWrapper) {
        const content = termWrapper.innerHTML;
        const parent = termWrapper.parentNode;
        const textNode = document.createTextNode(content);

        parent.replaceChild(textNode, termWrapper);

        // Обновляем состояние
        this._state = false;

        // Обновляем содержимое через API
        const currentBlockIndex = this.api.blocks.getCurrentBlockIndex();
        const block = this.api.blocks.getBlockByIndex(currentBlockIndex);

        if (block && block.holder) {
            const paragraph = block.holder.querySelector('.ce-paragraph');
            if (paragraph) {
                // Создаем событие input для обновления содержимого
                const event = new Event('input', { bubbles: true });
                paragraph.dispatchEvent(event);
            }
        }
    }

    checkState() {
        const termTag = this.api.selection.findParentTag(this.tag);
        this.state = !!termTag;

        if (termTag) {
            this.class = termTag.className;
        }
    }

    get state() {
        return this._state;
    }

    set state(state) {
        this._state = state;
        this.button.classList.toggle(this.api.styles.inlineToolButtonActive, state);
    }
}

// Ждем загрузку всех скриптов
document.addEventListener('DOMContentLoaded', () => {
    // Проверяем доступность ColorPlugin
    if (typeof ColorPlugin === 'undefined') {
        console.error('ColorPlugin не загружен!');
        return;
    }

    // Инициализация редактора
    const editor = new EditorJS({
        holder: 'editorjs',
        placeholder: 'Start writing or paste text here...',
        inlineToolbar: ['bold', 'italic', 'color', 'header'],
        tools: {
            paragraph: {
                class: window.Paragraph || require('@editorjs/paragraph'),
                inlineToolbar: true,
                config: {
                    preserveBlank: true
                }
            },
            color: {
                class: class extends ColorPlugin {
                    render() {
                        const button = super.render();

                        // Модифицируем стили после создания
                        setTimeout(() => {
                            const colorPicker = button.querySelector('xy-color-picker');
                            if (colorPicker) {
                                // Добавляем стили в Shadow DOM
                                const style = document.createElement('style');
                                style.textContent = `
                                    :host {
                                        all: unset !important;
                                        display: inline-flex !important;
                                        align-items: center !important;
                                        justify-content: center !important;
                                        padding: 0 !important;
                                        margin: 0 !important;
                                    }
                                    xy-popover {
                                        padding: 0 !important;
                                        margin: 0 !important;
                                    }
                                    .color-btn {
                                        padding: 0 !important;
                                        margin: 0 !important;
                                        border: none !important;
                                        margin-bottom: 2px !important;
                                    }
                                `;

                                if (colorPicker.shadowRoot) {
                                    colorPicker.shadowRoot.appendChild(style);
                                }

                                // Модифицируем размеры напрямую
                                colorPicker.style.cssText = `
                                    width: 34px !important;
                                    height: 34px !important;
                                    display: inline-flex !important;
                                    align-items: center !important;
                                    justify-content: center !important;
                                    padding: 0 !important;
                                    margin: 0 !important;
                                `;
                            }
                        }, 0);

                        return button;
                    }
                },
                config: {
                    colorCollections: Object.values(SS14BBCodeParser.colorMap),
                    defaultColor: '#000000',
                    type: 'text',
                    customPicker: true
                }
            },
            header: {
                class: HeaderTool
            }
        },
        defaultBlock: 'paragraph',
        onKeyDown: (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.execCommand('insertHTML', false, '\n');
                return false;
            }
        },
        data: {
            blocks: [{
                type: 'paragraph',
                data: {
                    text: ''
                }
            }]
        },
        onChange: async () => {
            const savedData = await editor.save();
            if (savedData.blocks.length > 0) {
                // Объединяем текст всех блоков
                const fullText = savedData.blocks.map(block => block.data.text).join('\n');
                const bbcode = SS14BBCodeParser.toBBCode(fullText);
                document.getElementById('bbcode-editor').value = bbcode;
            }
        }
    });

    // Обработчик Enter на уровне DOM
    document.getElementById('editorjs').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.execCommand('insertHTML', false, '\n');
            return false;
        }
    }, true);

    // Обработчик вставки текста
    document.getElementById('editorjs').addEventListener('paste', async (e) => {
        e.preventDefault();
        const text = e.clipboardData.getData('text/plain');

        try {
            // Предварительная обработка текста для нормализации переносов строк
            const normalizedText = text
                // Убираем пустые строки в начале и конце
                .trim()
                // Сохраняем пробелы в начале строк, заменяя их на маркер
                .replace(/^[ ]+/gm, match => '§'.repeat(match.length))
                // Сохраняем множественные пробелы внутри строк
                .replace(/[ ]{2,}/g, match => '§'.repeat(match.length))
                // Обрабатываем последовательные теги, сохраняя пробелы после них
                .replace(/\]([ ]*)\n([ ]*)\[/g, ']$1\n$2[')
                // Восстанавливаем маркеры пробелов обратно в пробелы
                .replace(/§/g, ' ');

            const html = SS14BBCodeParser.toHTML(normalizedText);

            // Очищаем все блоки
            await editor.blocks.clear();

            // Создаем новый блок с вставленным текстом
            await editor.blocks.insert('paragraph', {
                text: html
            });

            // Обновляем BB-код
            const savedData = await editor.save();
            if (savedData.blocks.length > 0) {
                const bbcode = SS14BBCodeParser.toBBCode(savedData.blocks[0].data.text);
                document.getElementById('bbcode-editor').value = bbcode;
            }
        } catch (error) {
            console.error('Error processing paste:', error);
            // В случае ошибки, пробуем вставить как обычный текст
            try {
                await editor.blocks.clear();
                await editor.blocks.insert('paragraph', {
                    text: text.trim()
                });
            } catch (fallbackError) {
                console.error('Fallback paste failed:', fallbackError);
            }
        }
    });

    // Обработчик переключения вкладок
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', async (e) => {
            if (e.target.getAttribute('href') === '#bbcode') {
                const savedData = await editor.save();
                if (savedData.blocks.length > 0) {
                    // Объединяем текст всех блоков
                    const fullText = savedData.blocks.map(block => block.data.text).join('\n');
                    const bbcode = SS14BBCodeParser.toBBCode(fullText);
                    document.getElementById('bbcode-editor').value = bbcode;
                }
            }
        });
    });
});
