<!DOCTYPE html>
<html>
<head>
    <title>Realizar Pagamentos aos Vendedores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Realizar Pagamentos aos Vendedores</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form id="payoutForm">
                            @csrf
                            <div class="mb-3">
                                <h5>Vendedor 1</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="email1" class="form-label">Email PayPal</label>
                                        <input type="email" class="form-control" id="email1" name="email1" value="victorhugo.almeidamag@gmail.com" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amount1" class="form-label">Valor (R$)</label>
                                        <input type="number" class="form-control" id="amount1" name="amount1" value="45.00" step="0.01" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5>Vendedor 2</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="email2" class="form-label">Email PayPal</label>
                                        <input type="email" class="form-control" id="email2" name="email2" value="victor.fut_@hotmail.com" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amount2" class="form-label">Valor (R$)</label>
                                        <input type="number" class="form-control" id="amount2" name="amount2" value="45.00" step="0.01" min="1" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Nota para os vendedores</label>
                                <input type="text" class="form-control" id="note" name="note" value="Pagamento pela venda #123">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Realizar Pagamentos</button>
                            </div>
                        </form>
                        
                        <!-- Seção para verificar o status -->
                        <div class="mt-4 pt-4 border-top" id="statusCheckSection" style="display: none;">
                            <h5>Verificar Status do Pagamento</h5>
                            
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="batchIdInput" placeholder="ID do lote (payout_batch_id)">
                                <button class="btn btn-outline-secondary" type="button" id="checkStatusBtn">Verificar Status</button>
                            </div>
                            
                            <div id="statusResult" class="mt-3"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="payoutResult"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let lastBatchId = '';
            
            $('#payoutForm').on('submit', function(e) {
                e.preventDefault();
                
                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processando...');
                
                // Preparar os dados dos pagamentos
                const payoutData = {
                    items: [
                        {
                            email: $('#email1').val(),
                            amount: $('#amount1').val(),
                            note: $('#note').val()
                        },
                        {
                            email: $('#email2').val(),
                            amount: $('#amount2').val(),
                            note: $('#note').val()
                        }
                    ],
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                // Enviar requisição AJAX
                $.ajax({
                    url: '{{ route("paypal.payout") }}',
                    type: 'POST',
                    data: payoutData,
                    success: function(response) {
                        $('#payoutResult').html(`
                            <div class="alert alert-success mt-3">
                                ${response.message}
                            </div>
                        `);
                        
                        // Mostrar seção de verificação de status e preencher o ID do lote
                        if (response.batch_id) {
                            lastBatchId = response.batch_id;
                            $('#batchIdInput').val(response.batch_id);
                            $('#statusCheckSection').show();
                        }
                        
                        $('#submitBtn').prop('disabled', false).text('Realizar Pagamentos');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocorreu um erro ao processar os pagamentos.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        
                        $('#payoutResult').html(`
                            <div class="alert alert-danger mt-3">
                                ${errorMessage}
                            </div>
                        `);
                        $('#submitBtn').prop('disabled', false).text('Realizar Pagamentos');
                    }
                });
            });
            
            // Verificar status de um payout
            $('#checkStatusBtn').on('click', function() {
                const batchId = $('#batchIdInput').val();
                
                if (!batchId) {
                    alert('Por favor, informe o ID do lote de pagamento');
                    return;
                }
                
                $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                
                $.ajax({
                    url: '{{ route("paypal.check-status") }}',
                    type: 'POST',
                    data: {
                        batch_id: batchId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        let statusHTML = `
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    Status do Lote: <strong>${response.status}</strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>ID do Lote:</strong> ${response.batch_id}</p>
                                    <p><strong>Total de Itens:</strong> ${response.total_items}</p>
                                    <p><strong>Itens Processados:</strong> ${response.processed_items}</p>
                                    <p><strong>Data de Criação:</strong> ${response.time}</p>
                                    
                                    <h6 class="mt-3">Detalhes dos Pagamentos:</h6>
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Destinatário</th>
                                                <th>Valor</th>
                                                <th>Status</th>
                                                <th>ID da Transação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        `;
                        
                        if (response.items && response.items.length > 0) {
                            response.items.forEach(item => {
                                statusHTML += `
                                    <tr>
                                        <td>${item.receiver}</td>
                                        <td>${item.amount} ${item.currency}</td>
                                        <td>${item.status}</td>
                                        <td>${item.transaction_id}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            statusHTML += `
                                <tr>
                                    <td colspan="4" class="text-center">Nenhum detalhe disponível</td>
                                </tr>
                            `;
                        }
                        
                        statusHTML += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                        
                        $('#statusResult').html(statusHTML);
                        $('#checkStatusBtn').prop('disabled', false).text('Verificar Status');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocorreu um erro ao verificar o status do pagamento.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        
                        $('#statusResult').html(`
                            <div class="alert alert-danger">
                                ${errorMessage}
                            </div>
                        `);
                        $('#checkStatusBtn').prop('disabled', false).text('Verificar Status');
                    }
                });
            });
        });
    </script>
</body>
</html>