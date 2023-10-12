<section class="content">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Bulan Ini</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Omset</span>
                    <span class="info-box-number" id="h_omt"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-ios-cart-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Penjualan</span>
                    <span class="info-box-number" id="h_ord"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-balance-scale"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Rata-rata</span>
                    <span class="info-box-number" id="h_avg"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Member Baru</span>
                    <span class="info-box-number" id="h_mbr"></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!--date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))))-->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="salesChart" style="height: 180px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
                <div class="box-footer">
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <div id="p_omt"></div>
                                <h5 class="description-header"></h5>
                                <span class="description-text">Omset</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <div id="p_ord"></div>
                                <h5 class="description-header"></h5>
                                <span class="description-text">Penjualan</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block border-right">
                                <div id="p_avg"></div>
                                <h5 class="description-header"></h5>
                                <span class="description-text">Rata-rata</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3 col-xs-6">
                            <div class="description-block">
                                <div id="p_mbr"></div>
                                <h5 class="description-header"></h5>
                                <span class="description-text">Member Baru</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<script>
    $(document).ready(function () {
        get_data();
    });

    function get_data() {
        $.ajax({
            url: "<?=base_url().'site/dashboard/get_data'?>",
            type: "GET",
            dataType: "JSON",
            beforeSend: function() {
                $('body').append('<div class="first-loader"><img src="<?=base_url().'/assets/images/spin.svg'?>"></div>');
            },
            complete: function() {
                $('.first-loader').remove();
            },
            success: function (res) {
                $("#h_omt").text(res.head['omset']);
                $("#h_ord").text(res.head['orders']);
                $("#h_avg").text(res.head['avg']);
                $("#h_mbr").text(res.head['member']);
                $("#p_omt").html(res.persentase['omset']);
                $("#p_ord").html(res.persentase['orders']);
                $("#p_avg").html(res.persentase['avg']);
                $("#p_mbr").html(res.persentase['member']);
                generate_cart(res.label, res.data);
            }
        });
    }

    function chart_omset(labels, data) {
        var ctx = document.getElementById("salesChart").getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgb(75, 192, 192)'
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Omset Masuk Per Jam',
                    fontSize: '20'
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value) {
                                return to_rp(value, '-');
                            },
                            beginAtZero:true
                        }
                    }]
                },
                tooltips: {
                    displayColors: false,
                    callbacks: {
                        label: function(data) {
                            return 'Rp '+to_rp(data.yLabel, '-');
                        }
                    }
                }
            }
        });
    }

    function generate_cart(label_, data_) {
        // Get context with jQuery - using jQuery's .get() method.
        var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var salesChart = new Chart(salesChartCanvas);

        var salesChartData = {
            labels: label_,
            datasets: [
                {
                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: data_
                }
            ]
        };

        var salesChartOptions = {
            //Boolean - If we should show the scale at all
            showScale: true,
            scaleLabel: function(label){return to_rp(label.value, '-');},
            tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= 'Rp '+to_rp(value,'-') %>",
            scaleShowGridLines: false,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            scaleGridLineWidth: 1,
            scaleShowHorizontalLines: true,
            scaleShowVerticalLines: true,
            bezierCurve: true,
            bezierCurveTension: 0.3,
            pointDot: false,
            pointDotRadius: 4,
            pointDotStrokeWidth: 1,
            pointHitDetectionRadius: 20,
            datasetStroke: true,
            datasetStrokeWidth: 2,
            datasetFill: true,
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
            maintainAspectRatio: true,
            responsive: true
        };

        //Create the line chart
        salesChart.Line(salesChartData, salesChartOptions);
    }
</script>