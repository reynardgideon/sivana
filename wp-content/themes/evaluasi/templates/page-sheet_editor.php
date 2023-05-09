<?php

/**
 * Template Name: Sheet Editor
 * @package WordPress
 */

?>

<?php
$data = array();
if (isset($_GET['action'])) {
    $fields = array(
        'peserta' => array(
            'nama_lengkap' => 350,
            'nip' => 200,
            'unit_kerja' => 600
        )
    );

    $action_function = 'sheet_' . $_GET['action'];

    $r = $action_function($_GET);
}

function sheet_tambah_peserta($q)
{
    global $data;
    $rows = array(
        'name' => 'Sheet 1',
        'styles' => array(
            "0" => array(
                'align' => 'center',
                'font' => array(
                    'bold' => true
                )
            )
        ),
        'rows' => array(
            array(
                'cells' => array(
                    array(
                        'text' => 'Nama Lengkap',
                        'style' => 0
                    ),
                    array(
                        'text' => 'NIP',
                        'style' => 0
                    ),
                    array(
                        'text' => 'Unit Kerja',
                        'style' => 0
                    )
                )
            )
        ),

        'cols' => array(
            array(
                'width' => 350,
            ),
            array(
                'width' => 350,
            ),
            array(
                'width' => 350,
            )
        )
    );

    $data = json_encode($rows);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Sheet Editor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/x-data-spreadsheet@1.1.5/dist/xspreadsheet.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <style>
        .icon:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <?= $data ?>
    <div class="pl-5 pt-4 has-background-light">
        <button class="button is-danger mx-3">Cancel</button>
        <button class="button is-link">Submit</button>
    </div>
    <div id="x-spreadsheet"></div>
    <script>
        $(document).ready(function($) {
            var xs = x_spreadsheet('#x-spreadsheet', {
                mode: 'edit', // edit | read
                showToolbar: false,
                showGrid: true,
                showContextmenu: true,

                row: {
                    len: 100,
                    height: 30,
                },
                col: {
                    len: 26,
                    width: 100,
                    indexWidth: 60,
                    minWidth: 60,
                },
                style: {
                    bgcolor: '#ffffff',
                    align: 'left',
                    valign: 'middle',
                    textwrap: false,
                    strike: false,
                    underline: false,
                    color: '#0a0a0a',
                    font: {
                        name: 'Helvetica',
                        size: 10,
                        bold: false,
                        italic: false,
                    },
                },
            });

            const data = [{
                name: "sheet2",
                freeze: "A1",
                styles: [{
                    align: "center",
                    font: {
                        bold: true
                    }
                }],
                rows: [{
                    cells: [{
                        text: "Nama",
                        style: 0
                    }, {
                        text: "NIP",
                        style: 0
                    }, {
                        text: "Unit Kerja",
                        style: 0
                    }]
                }],
                cols: [{
                        width: 350
                    },
                    {
                        width: 200
                    },
                    {
                        width: 600
                    }
                ]
            }];

            const text = [{
                "name": "Sheet 1",
                "styles": [{
                    "align": "center",
                    "font": {
                        "bold": "true"
                    }
                }],
                "rows": {
                    "0": {
                        "cells": [{
                            "text": "Nama Lengkap",
                            "style": "0"
                        }, {
                            "text": "NIP",
                            "style": "0"
                        }, {
                            "text": "Unit Kerja",
                            "style": "0"
                        }]
                    }
                },
                "cols": {
                    "0": {
                        "width": "350"
                    },
                    "1": {
                        "width": "350"
                    },
                    "2": {
                        "width": "350"
                    }
                }
            }];
            //var data2 = JSON.parse(text);
            xs.loadData(JSON.parse('<?= $data ?>'));



            $('#save').click(function() {

                var ajaxurl = 'https://evaluasi.knpk.xyz/wp-admin/admin-ajax.php';
                $(this).addClass('is-loading');

                var data = {
                    action: 'save_sheet',
                    json_data: xs.getData(),
                    sheet: <?= get_the_id() ?>
                };
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,

                    success: function(response) {
                        console.log(response);
                        $('#save').removeClass('is-loading');
                    }
                });
            });
        });
    </script>
    <script src="https://unpkg.com/x-data-spreadsheet@1.1.5/dist/xspreadsheet.js"></script>
</body>

</html>