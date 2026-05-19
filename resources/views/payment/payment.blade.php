<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Midtrans</title>

    <script
        type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}">
    </script>

    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card{
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        button{
            background: #007bff;
            color: white;
            border: none;l
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover{
            background: #0056b3;
        }

        h1{
            margin-bottom: 20px;
        }

        p{
            color: #666;
        }
    </style>
</head>
<body>

<div class="card">

    <h1>Pembayaran Midtrans</h1>

    <p>Total pembayaran:</p>

    <h2>Rp 10.000</h2>

    <button id="pay-button">
        Bayar Sekarang
    </button>

</div>

<script>

document.getElementById('pay-button').onclick = function () {

    fetch('/payment-token')
    .then(response => response.json())
    .then(token => {

        console.log(token);

        snap.pay(token, {

            onSuccess: function(result){

                alert("Pembayaran berhasil");

                console.log(result);

                window.location.href = "/payment-success";

            },

            onPending: function(result){

                alert("Menunggu pembayaran");

                console.log(result);

            },

            onError: function(result){

                alert("Pembayaran gagal");

                console.log(result);

            },

            onClose: function(){

                alert('Popup pembayaran ditutup');

            }

        });

    })
    .catch(error => {

        console.log(error);

        alert('Terjadi error');

    });

};

</script>

</body>
</html>