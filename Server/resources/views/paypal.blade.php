<!DOCTYPE html>
<html>
<head>
    <title>Pagamento com PayPal</title>
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.sandbox.client_id') }}&currency=BRL&locale=pt_BR"></script>
</head>
<body>
    <div id="paypal-button-container"></div>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: 'BRL',
                            value: '100.00'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                // Captura o pagamento e depois redireciona
                return actions.order.capture().then(function(details) {
                    console.log('Captura realizada com sucesso:', details);
                    
                    // Redireciona para a página de sucesso com o ID do pedido
                    // Já não tentaremos capturar novamente no backend
                    window.location.href = "{{ route('paypal.success') }}?token=" + data.orderID;
                });
            },
            onCancel: function(data) {
                window.location.href = "{{ route('paypal.cancel') }}";
            },
            onError: function(err) {
                console.error('Erro do PayPal:', err);
                alert('Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.');
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>