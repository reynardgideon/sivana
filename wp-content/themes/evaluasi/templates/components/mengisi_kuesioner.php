<?php

function page_mengisi_kuesioner()
{
    $pod = pods('pelatihan', get_the_id());

    $total = count($pod->field('peserta.ID'));

    $data = json_decode($pod->field('rekap_evaluasi_level_1'));

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="card">
    <div class="card-body text-center">
        <h4>REKAPITULASI EVALUASI PENYELENGGARAAN</h4><br />
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-5">
                    <canvas id="rekap_kelulusan" class="mt-3"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const ctx = document.getElementById('rekap_kelulusan');

    const data = {
        labels: ['Mengisi Kuesioner', 'Tidak Mengisi Kuesioner'],
        datasets: [{
            label: ' ',
            data: [<?= $data->responden ?>, <?= $total - $data->responden ?>],
            backgroundColor: ['rgb(54, 162, 235)', 'rgb(255, 99, 132)'],
            hoverOffset: 4
        }]
    };

    new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: 'rgb(255, 99, 132)'
                    }
                }
            }
        }
    });
</script>

<?php } ?>