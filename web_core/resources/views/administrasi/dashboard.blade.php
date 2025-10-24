@include('administrasi._header')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="small-box bg-success" style="opacity: 0.9">
                    <div class="inner">
                        <div class="inline p-2">
                            <h6>Mahasiswa Login <br> Hari ini</h6>
                            <h3>{{ number_format($statistics['mhs_today'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-success" style="opacity: 0.8">
                    <div class="inner">
                        <div class="inline p-2">
                            <h6>Mahasiswa Login <br> Bulan ini</h6>
                            <h3>{{ number_format($statistics['mhs_thism'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-info" style="opacity: 0.9">
                    <div class="inner">
                        <div class="inline p-2">
                            <h6>Pegawai Login <br> Hari ini</h6>
                            <h3>{{ number_format($statistics['peg_today'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-info" style="opacity: 0.8">
                    <div class="inner">
                        <div class="inline p-2">
                            <h6>Mahasiswa Login <br> Bulan ini</h6>
                            <h3>{{ number_format($statistics['peg_thism'], 0) }}</h3>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line"></i> Statistik Login Pengguna 30 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="loginChart"></canvas>
            </div>
        </div>
    </div>
</div>
@section('addon_footer')
    <script src="{{ url('assets/bo/plugins/chart.js/Chart.min.js') }} "></script>
@endsection
@section('addonjs')
    <script>
        const data = {!! $statistics['last_30d'] !!};
        const labels = [...new Set(data.map(d => d.login_time_ymd))];
        const roles = [...new Set(data.map(d => d.role_nama))];
        const datasets = roles.map(role => {
            return {
                label: role,
                data: labels.map(date => {
                    const found = data.find(d => d.login_time_ymd === date && d.role_nama === role);
                    return found ? found.total : 0;
                }),
                borderWidth: 2,
                borderColor: role === "Admin PT" ? "blue" : "green",
                backgroundColor: role === "Admin PT" ? "rgba(0,0,255,0.2)" : "rgba(0,255,0,0.2)",
                fill: false
            }
        });

        new Chart(document.getElementById("loginChart"), {
            type: 'line', // bisa diganti 'bar'
            data: {
                labels,
                datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
@include('administrasi._footer')
