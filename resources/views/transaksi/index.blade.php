@extends('layouts.app')

@section('content')
<style>
    .print-only{
        display: none;
    }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Stok barang</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="table_barang" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Keranjang</div>
                <div class="card-body">
                {!! Form::open(['route' => 'transaksi.store', 'id' => 'buat_transaksi']) !!}
                    <table class="table" id="t3">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="keranjang">
                        </tbody>
                        <tfoot>
                            <td colspan="2" align="right">Total</td>
                            <td colspan="2" align="right" id="total"></td>
                        </tfoot>
                    </table>
                </div>
                <input type="submit" value="Beli" class="btn btn-block btn-primary">
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="print-area" class="print-only">
</div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/print.js') }}"></script>
    <script>
        var _token = "{{csrf_token()}}";

        $(function(){
            
            var table = $("#table_barang").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url : '{{ route("getBarangForTransaksi") }}'
                },
                columns: [
                    { data: 'nama'},
                    { data: 'harga_jual'},
                    { data: 'stok' },
                    { data: 'actions', orderable: false, searchable: false}
                ]
            });

            var row = table.row();
            var total = 0;

            $('#table_barang tbody').on( 'click', '.increment', function () {
                var data = $(this).data('barang');

                var barang = table.row($(this).closest("tr")).data();
                if(parseInt(barang['stok']) > 0){
                    barang['stok'] -= 1;
                    table.row($(this).closest("tr")).data(barang);

                    var find = $("#keranjang").find(`#row_${data.id}`)[0];
                    if(!find){
                        var id = data.id;
                        var qty = 1;
                        var harga = data.harga_jual;

                        $("#keranjang").append(`
                            <tr id="row_${id}">
                                <td>${data.nama}</td>
                                <input type="hidden" value="${data.nama}" name="nama[${id}]"/>
                                <td id="view_qty_${id}" align="right">${qty}</td>
                                <input type="hidden" value="${qty}" name="qty[${id}]" id="qty_${id}"/>
                                <td id="view_harga_${id}" align="right">${harga}</td>
                                <input type="hidden" value="${harga}" name="harga[${id}]" id="harga_${id}"/>
                            </tr>
                        `);
                    }
                    else {
                        var id = data.id;
                        var qty = parseInt($(`#qty_${id}`).val()) + 1;
                        var harga = parseInt($(`#harga_${id}`).val()) + parseInt(data.harga_jual);
                        
                        $(`#view_qty_${id}`).html(qty);
                        $(`#qty_${id}`).val(qty);
                        $(`#view_harga_${id}`).html(harga);
                        $(`#harga_${id}`).val(harga);
                    }

                    total+=data.harga_jual;
                    $("#total").html(total);
                    
                }
                else{
                    $(this).addClass('disabled');
                }

                
                // var id = $(this).data('id');
                // var barang = $(this).data('barang');
                // $("#input_id").val(barang.id);
                // $("#input_nama").val(barang.nama);
                // $("#barang_title").html(barang.nama);
                // $("#input_harga").val(barang.harga_jual);
                // $("#input_stok").val(barang.stok);
                // $("#input_qty").val("");
            })

            $('#table_barang tbody').on( 'click', '.decrement', function () {
                var data = $(this).data('barang');

                var barang = table.row($(this).closest("tr")).data();
                if(data.stok > parseInt(barang['stok'])){
                    barang['stok'] += 1;
                    table.row($(this).closest("tr")).data(barang);
                    
                    var id = data.id;

                    if(parseInt($(`#qty_${id}`).val()) > 1){
                        var qty = parseInt($(`#qty_${id}`).val()) - 1;
                        var harga = parseInt($(`#harga_${id}`).val()) - parseInt(data.harga_jual);

                        $(`#view_qty_${id}`).html(qty);
                        $(`#qty_${id}`).val(qty);
                        $(`#view_harga_${id}`).html(harga);
                        $(`#harga_${id}`).val(harga);
                    }
                    else
                    {
                        $(`#row_${id}`).remove();
                    }
                    total-=data.harga_jual;
                    $("#total").html(total);

                }
                else{
                    $(this).addClass('disabled');
                }

            });

            $("#buat_transaksi").submit(function(e){
                e.preventDefault();
                var _this = $(this);
                
                
                Swal.fire({
                    input: 'text',
                    inputPlaceholder: 'Masukan uang tunai',
                })
                .then(function(result){
                    if(result.value){
                        $.ajax({
                            'url' : "{{route('transaksi.store')}}",
                            'method' : 'POST',
                            'data': _this.serialize() + "&tunai=" + result.value,
                            success: function(data){
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Print',
                                    cancelButtonText: 'Print nanti'
                                })
                                .then(function(result2){
                                    if(result2.value){
                                        $.ajax({
                                            url: '{{route("transaksi")}}' + "/print/" + data.data.id,
                                            method: 'GET',
                                            success: function (print_data){
                                                $('#print-area').html(print_data);
                                            },
                                            complete: function () {
                                                printJS({
                                                    printable: 'print-area', 
                                                    type: 'html'
                                                });
                                            }
                                        });
                                    }
                                })
                            }
                        });
                    }
                })
                
            });
        })

    </script>
@endsection