<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
include 'conexao.php';

// Returns the complete default settings array
function get_default_settings()
{
    return [
        'font_style' => 'Roboto, Arial, sans-serif',
        'font_size' => '14px',
        'font_weight' => '400',
        'font_italic' => 0,
        'font_color' => '#333333',
        'background_color' => '#f4f4f4',
        'link_color' => '#1a0dab',
        'link_hover_color' => '#67458b',
        'table_background_color' => '#ffffff',
        'table_border_color' => '#dddddd',
        'header_color' => '#67458b',
        'header_font_color' => '#ffffff',
        'row_even_color' => '#f9f9f9',
        'row_hover_color' => '#f1f1f1',
        'table_font_size' => '13px',
        'show_summary_panel' => 1,
        'show_id' => 1,
        'show_images' => 1,
        'show_eshop' => 1,
        'show_condition' => 1,
        'show_oem' => 1,
        'show_equipment' => 1,
        'show_brand' => 1,
        'show_model' => 1,
        'show_configuration' => 1,
        'show_code' => 1,
        'show_description' => 1,
        'show_price' => 1,
        'show_qtd' => 1,
        'show_total' => 1,
        'show_location' => 1,
        'show_ire' => 1,
        'show_eur' => 1,
        'show_returns' => 1,
        'show_copy' => 1,
        'show_ebay' => 1,
        'show_last_edited_by' => 1,
        'show_status' => 1,
        'show_last_updated' => 1,
        'show_actions' => 1
    ];
}

function getUserSettings($conn, $userEmail)
{
    if (empty($userEmail)) return get_default_settings();
    $stmt = $conn->prepare("SELECT * FROM granna80_bdlinks.scrapyard_settings WHERE user_email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return array_merge(get_default_settings(), $result->fetch_assoc());
    } else {
        return get_default_settings();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $default_settings = get_default_settings();
    $settings_data = [];
    foreach ($default_settings as $key => $value) {
        if (substr($key, 0, 5) === 'show_') {
            $settings_data[$key] = isset($_POST[$key]) ? 1 : 0;
        } elseif (isset($_POST[$key])) {
            if (in_array($key, ['font_size', 'table_font_size'])) {
                $settings_data[$key] = htmlspecialchars($_POST[$key] . 'px');
            } else {
                $settings_data[$key] = str_replace(["'", '"'], '', $_POST[$key]);
            }
        }
    }
    $settings_data['font_italic'] = isset($_POST['font_italic']) ? 1 : 0;

    $schema = [];
    foreach ($default_settings as $key => $value) {
        $schema[$key] = is_int($value) ? 'i' : 's';
    }

    $columns = array_keys($settings_data);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $update_clause = implode(', ', array_map(fn($col) => "$col = VALUES($col)", $columns));

    $query = "INSERT INTO granna80_bdlinks.scrapyard_settings (user_email, " . implode(', ', $columns) . ")
              VALUES (?, $placeholders)
              ON DUPLICATE KEY UPDATE $update_clause";

    $stmt = $conn->prepare($query);

    $types = 's';
    foreach ($columns as $column) {
        $types .= $schema[$column];
    }

    $values = array_merge([$userEmail], array_values($settings_data));
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        $message = "Settings saved successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

$settings = getUserSettings($conn, $userEmail);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;1,400&family=Open+Sans:ital,wght@0,400;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&family=Montserrat:ital,wght@0,400;0,600;0,700;1,400&family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400&family=Nunito+Sans:ital,wght@0,400;0,700;1,400&family=Poppins:ital,wght@0,400;0,700;1,400&family=Raleway:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;700&family=Work+Sans:ital,wght@0,400;0,700;1,400&family=PT+Sans:ital,wght@0,400;0,700;1,400&family=Oswald:wght@400;700&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Lora:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:ital,wght@0,400;0,700;1,400&family=Arvo:ital,wght@0,400;0,700;1,400&family=Lobster&family=Pacifico&family=Bebas+Neue&family=Alfa+Slab+One&family=Source+Code+Pro:ital,wght@0,400;0,700;1,400&family=Inconsolata:wght@400;700&family=Fira+Code:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --default-bg: #f8f9fa;
            --panel-bg: #ffffff;
            --text-color: #212529;
            --border-color: #dee2e6;
            --accent-color: #0d6efd;
            --accent-hover: #0b5ed7;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: var(--default-bg);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }

        .main-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1600px;
            margin: auto;
        }

        .settings-panel,
        .preview-panel {
            background: var(--panel-bg);
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .settings-panel {
            flex: 1;
            min-width: 600px;
        }

        .preview-panel {
            flex: 1;
            min-width: 400px;
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        h1,
        h2 {
            color: inherit;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        h2 i {
            margin-right: 12px;
            color: var(--accent-color);
        }

        .form-group {
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        label {
            font-weight: 600;
            min-width: 200px;
        }

        input[type="color"],
        select {
            vertical-align: middle;
        }

        input[type="checkbox"] {
            transform: scale(1.5);
            cursor: pointer;
        }

        .color-preview {
            width: 24px;
            height: 24px;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            display: inline-block;
        }

        .range-slider-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-grow: 1;
        }

        .range-slider-group input {
            flex-grow: 1;
        }

        .range-slider-group span {
            font-weight: bold;
            color: var(--accent-color);
            min-width: 40px;
            text-align: right;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            border-top: 2px solid var(--border-color);
            padding-top: 20px;
        }

        button {
            background-color: var(--accent-color);
            color: white;
            padding: 12px 22px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: var(--accent-hover);
        }

        #reset-defaults-btn {
            background-color: #6c757d;
        }

        #reset-defaults-btn:hover {
            background-color: #5c636a;
        }

        .message {
            padding: 15px;
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        #preview-pane {
            transition: all 0.3s ease;
        }

        #preview-pane table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        #preview-pane th,
        #preview-pane td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }

        #preview-pane a {
            text-decoration: none;
            font-weight: bold;
        }

        #preview-pane a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="settings-panel">
            <h1></i>Advanced Settings</h1>
            <p><a href="index.php">[ Back ]</a></p>
            <?php if (isset($message)): ?><div class="message"><?= $message ?></div><?php endif; ?>

            <form method="POST" id="settings-form">
                <h2>General Styles</h2>
                <div class="form-group"><label for="font_style">Body Font</label>
                    <select id="font_style" name="font_style">
                        <optgroup label="Sans-Serif (Modern & Clean)">
                            <option>Roboto, Arial, sans-serif</option>
                            <option>Open Sans, sans-serif</option>
                            <option>Lato, sans-serif</option>
                            <option>Montserrat, sans-serif</option>
                            <option>Source Sans Pro, sans-serif</option>
                            <option>Nunito Sans, sans-serif</option>
                            <option>Poppins, sans-serif</option>
                            <option>Raleway, sans-serif</option>
                            <option>Inter, sans-serif</option>
                            <option>Work Sans, sans-serif</option>
                            <option>PT Sans, sans-serif</option>
                            <option>Oswald, sans-serif</option>
                            <option>Verdana, sans-serif</option>
                            <option>Arial, sans-serif</option>
                            <option>Helvetica, sans-serif</option>
                        </optgroup>
                        <optgroup label="Serif (Classic & Elegant)">
                            <option>Merriweather, serif</option>
                            <option>Lora, serif</option>
                            <option>Playfair Display, serif</option>
                            <option>EB Garamond, serif</option>
                            <option>Cormorant Garamond, serif</option>
                            <option>Arvo, serif</option>
                            <option>Georgia, serif</option>
                            <option>Times New Roman, Times, serif</option>
                        </optgroup>
                        <optgroup label="Display (Stylized)">
                            <option>Lobster, cursive</option>
                            <option>Pacifico, cursive</option>
                            <option>Bebas Neue, cursive</option>
                            <option>Alfa Slab One, cursive</option>
                        </optgroup>
                        <optgroup label="Monospace (Technical)">
                            <option>Source Code Pro, monospace</option>
                            <option>Inconsolata, monospace</option>
                            <option>Fira Code, monospace</option>
                            <option>Courier New, Courier, monospace</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group"><label for="font_italic">Italic Style</label><input type="checkbox" id="font_italic" name="font_italic"></div>
                <div class="form-group"><label for="font_weight">Font Weight</label>
                    <select id="font_weight" name="font_weight">
                        <option value="300">Light</option>
                        <option value="400">Normal</option>
                        <option value="600">Bold</option>

                    </select>
                </div>
                <div class="form-group"><label for="font_size">Body Font Size</label>
                    <div class="range-slider-group"><input type="range" id="font_size" name="font_size" min="6" max="32" step="1"><span id="font_size_value"></span></div>
                </div>
                <div class="form-group"><label for="background_color">Screen Background</label><input type="color" id="background_color" name="background_color"><span class="color-preview" id="background_color_preview"></span></div>
                <div class="form-group"><label for="font_color">Main Font Color</label><input type="color" id="font_color" name="font_color"><span class="color-preview" id="font_color_preview"></span></div>
                <div class="form-group"><label for="link_color">Link Color</label><input type="color" id="link_color" name="link_color"><span class="color-preview" id="link_color_preview"></span></div>
                <div class="form-group"><label for="link_hover_color">Link Hover Color</label><input type="color" id="link_hover_color" name="link_hover_color"><span class="color-preview" id="link_hover_color_preview"></span></div>

                <h2>Table Styles</h2>
                <div class="form-group"><label for="table_font_size">Table Font Size</label>
                    <div class="range-slider-group"><input type="range" id="table_font_size" name="table_font_size" min="6" max="32" step="1"><span id="table_font_size_value"></span></div>
                </div>
                <div class="form-group"><label for="table_background_color">Table Background</label><input type="color" id="table_background_color" name="table_background_color"><span class="color-preview" id="table_background_color_preview"></span></div>
                <div class="form-group"><label for="table_border_color">Table Border Color</label><input type="color" id="table_border_color" name="table_border_color"><span class="color-preview" id="table_border_color_preview"></span></div>
                <div class="form-group"><label for="header_color">Header Background</label><input type="color" id="header_color" name="header_color"><span class="color-preview" id="header_color_preview"></span></div>
                <div class="form-group"><label for="header_font_color">Header Font Color</label><input type="color" id="header_font_color" name="header_font_color"><span class="color-preview" id="header_font_color_preview"></span></div>
                <div class="form-group"><label for="row_even_color">Even Row Color</label><input type="color" id="row_even_color" name="row_even_color"><span class="color-preview" id="row_even_color_preview"></span></div>
                <div class="form-group"><label for="row_hover_color">Row Hover Color</label><input type="color" id="row_hover_color" name="row_hover_color"><span class="color-preview" id="row_hover_color_preview"></span></div>

                <h2>Visible Columns</h2>
                <table class="columns-table">
                    <tr style="background-color: #f0f8ff;">
                        <td><strong>Datalist Panel</strong></td>
                        <td><input type="checkbox" name="show_summary_panel"></td>
                    </tr>
                    <tr>
                        <td>ID</td>
                        <td><input type="checkbox" name="show_id"></td>
                    </tr>
                    <tr>
                        <td>Images</td>
                        <td><input type="checkbox" name="show_images"></td>
                    </tr>
                    <tr>
                        <td>Eshop</td>
                        <td><input type="checkbox" name="show_eshop"></td>
                    </tr>
                    <tr>
                        <td>Condition</td>
                        <td><input type="checkbox" name="show_condition"></td>
                    </tr>
                    <tr>
                        <td>OEM</td>
                        <td><input type="checkbox" name="show_oem"></td>
                    </tr>
                    <tr>
                        <td>Equipment</td>
                        <td><input type="checkbox" name="show_equipment"></td>
                    </tr>
                    <tr>
                        <td>Brand</td>
                        <td><input type="checkbox" name="show_brand"></td>
                    </tr>
                    <tr>
                        <td>Model</td>
                        <td><input type="checkbox" name="show_model"></td>
                    </tr>
                    <tr>
                        <td>Configuration</td>
                        <td><input type="checkbox" name="show_configuration"></td>
                    </tr>
                    <tr>
                        <td>Code</td>
                        <td><input type="checkbox" name="show_code"></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><input type="checkbox" name="show_description"></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type="checkbox" name="show_price"></td>
                    </tr>
                    <tr>
                        <td>QTD</td>
                        <td><input type="checkbox" name="show_qtd"></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><input type="checkbox" name="show_total"></td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td><input type="checkbox" name="show_location"></td>
                    </tr>
                    <tr>
                        <td>IRE</td>
                        <td><input type="checkbox" name="show_ire"></td>
                    </tr>
                    <tr>
                        <td>EUR</td>
                        <td><input type="checkbox" name="show_eur"></td>
                    </tr>
                    <tr>
                        <td>Returns</td>
                        <td><input type="checkbox" name="show_returns"></td>
                    </tr>
                    <tr>
                        <td>Copy</td>
                        <td><input type="checkbox" name="show_copy"></td>
                    </tr>
                    <tr>
                        <td>eBay</td>
                        <td><input type="checkbox" name="show_ebay"></td>
                    </tr>
                    <tr>
                        <td>Last Edited By</td>
                        <td><input type="checkbox" name="show_last_edited_by"></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><input type="checkbox" name="show_status"></td>
                    </tr>
                    <tr>
                        <td>Last Updated</td>
                        <td><input type="checkbox" name="show_last_updated"></td>
                    </tr>
                    <tr>
                        <td>Actions</td>
                        <td><input type="checkbox" name="show_actions"></td>
                    </tr>
                </table>

                <div class="button-group">
                    <button type="submit"><i class="fas fa-save"></i> Save Settings</button>
                    <button type="button" id="reset-defaults-btn"><i class="fas fa-undo"></i> Reset to Default</button>
                </div>
            </form>
        </div>
        <div class="preview-panel">
            <div id="preview-pane">
                <h2 id="preview-h2">Live Preview Panel</h2>
                <p id="preview-p">Text and elements below will update in real-time. Also, check out this <a id="preview-a" href="#">example link</a>.</p>
                <table id="preview-table">
                    <thead>
                        <tr id="preview-tr-head">
                            <th id="preview-th">Header</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="preview-tr-odd">
                            <td>Odd Row</td>
                            <td>Value 1</td>
                        </tr>
                        <tr id="preview-tr-even">
                            <td>Even Row</td>
                            <td>Value 2</td>
                        </tr>
                        <tr id="preview-tr-hover">
                            <td>Hover Row</td>
                            <td>(Hover over me)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('settings-form');
            const previewPane = document.getElementById('preview-pane');
            const defaultSettings = <?= json_encode(get_default_settings()) ?>;
            const initialSettings = <?= json_encode($settings) ?>;

            // --- Controls Mapping ---
            const controls = {};
            const controlIds = [
                'font_style', 'font_size', 'font_weight', 'font_italic', 'background_color', 'font_color',
                'link_color', 'link_hover_color', 'table_background_color', 'table_border_color',
                'header_color', 'header_font_color', 'row_even_color', 'row_hover_color', 'table_font_size'
            ];
            controlIds.forEach(id => {
                controls[id] = form.elements[id];
            });

            // --- Preview Elements Mapping ---
            const previewElements = {
                table: document.getElementById('preview-table'),
                th: document.getElementById('preview-th'),
                trEven: document.getElementById('preview-tr-even'),
                a: document.getElementById('preview-a'),
            };
            const hoverStyleSheet = document.createElement('style');
            document.head.appendChild(hoverStyleSheet);

            // --- Main Update Function ---
            function updatePreview() {

             document.body.style.backgroundColor = controls.background_color.value;
                // General Styles
                previewPane.style.fontFamily = controls.font_style.value;
                previewPane.style.fontSize = controls.font_size.value + 'px';
                previewPane.style.fontWeight = controls.font_weight.value;
                previewPane.style.fontStyle = controls.font_italic.checked ? 'italic' : 'normal';
                previewPane.style.color = controls.font_color.value;

                // Links
                previewElements.a.style.color = controls.link_color.value;

                // Table Styles
                previewElements.table.style.backgroundColor = controls.table_background_color.value;
                previewElements.table.style.borderColor = controls.table_border_color.value;
                previewElements.table.style.fontSize = controls.table_font_size.value + 'px';

                previewPane.querySelectorAll('th, td').forEach(cell => cell.style.borderColor = controls.table_border_color.value);

                previewElements.th.style.backgroundColor = controls.header_color.value;
                previewElements.th.style.color = controls.header_font_color.value;

                previewElements.trEven.style.backgroundColor = controls.row_even_color.value;

                // Styles that require a stylesheet (for :hover)
                hoverStyleSheet.innerHTML = `
            #preview-a:hover { color: ${controls.link_hover_color.value} !important; }
            #preview-tr-hover:hover { background-color: ${controls.row_hover_color.value} !important; }
        `;
            }

            // --- Form Hydration and Reset ---
            function setFormValues(settingsObject) {
                for (const key in settingsObject) {
                    const control = form.elements[key];
                    if (control) {
                        if (control.type === 'checkbox') {
                            control.checked = !!parseInt(settingsObject[key]);
                        } else if (control.type === 'range') {
                            control.value = parseInt(settingsObject[key], 10);
                            control.dispatchEvent(new Event('input')); // Trigger event to update display span
                        } else {
                            control.value = settingsObject[key];
                        }
                    }
                }
                document.querySelectorAll('input[type="color"]').forEach(input => {
                    const previewSpan = document.getElementById(input.id + '_preview');
                    if (previewSpan) previewSpan.style.backgroundColor = input.value;
                });
            }

            // --- Event Listeners ---
            form.addEventListener('input', updatePreview);

            ['font_size', 'table_font_size'].forEach(id => {
                const slider = document.getElementById(id);
                const display = document.getElementById(id + '_value');
                if (slider && display) {
                    slider.addEventListener('input', function() {
                        display.textContent = this.value + 'px';
                    });
                }
            });

            document.getElementById('reset-defaults-btn').addEventListener('click', function() {
                if (confirm('Are you sure you want to restore all settings to default?')) {
                    setFormValues(defaultSettings);
                    updatePreview();
                }
            });

            // --- Initialization ---
            setFormValues(initialSettings);
            updatePreview();
        });
    </script>
</body>

</html>