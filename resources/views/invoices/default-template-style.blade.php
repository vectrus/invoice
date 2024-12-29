<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        color: #333;
        margin: 0;
        padding: 20px;
    }

    .invoice {
        max-width: 800px;
        margin: 0 auto;
    }

    .header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
    }

    .company-info h1 {
        color: #2c3e50;
        margin: 0 0 10px 0;
        font-size: 24px;
    }

    .company-info p {
        margin: 0;
        color: #666;
    }

    .invoice-info {
        text-align: right;
    }

    .invoice-info h2 {
        color: #2c3e50;
        margin: 0 0 15px 0;
    }

    .info-table {
        border-collapse: collapse;
    }

    .info-table td {
        padding: 5px 0;
        text-align: left;
    }

    .info-table td:first-child {
        padding-right: 20px;
    }

    .client-info {
        margin-bottom: 30px;
    }

    .client-info h3 {
        color: #2c3e50;
        margin: 0 0 10px 0;
    }

    .client-details p {
        margin: 0;
    }

    .client-name {
        font-weight: bold;
        font-size: 16px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .items-table th,
    .items-table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .items-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .items-table tfoot tr td {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    .footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #eee;
    }

    .payment-info {
        margin-bottom: 20px;
    }

    .payment-info h4 {
        color: #2c3e50;
        margin: 0 0 10px 0;
    }

    .payment-info p {
        margin: 5px 0;
    }

    .terms {
        font-size: 12px;
        color: #666;
    }

    @media print {
        body {
            padding: 0;
        }

        .invoice {
            max-width: none;
        }
    }
</style>
