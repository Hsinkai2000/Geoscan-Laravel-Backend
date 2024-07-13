<!DOCTYPE html>
<html>

<head>
    <style>
        html {
            -webkit-print-color-adjust: exact;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
            padding: 8px 0;
            z-index: 10;
            background-color: #bd6747;
            color: #f5f5f5;
        }

        .left {
            float: left;
            margin-left: 10px;
        }

        .right {
            float: right;
            margin-right: 10px;
        }

        .content {
            padding-top: 8px;
            border-top: 1px solid #f5f5f5;
        }
    </style>
</head>

<body>
    <div class="footer">
        <div class="content">
            <div class="left">Geoscan Data Tracking System</div>
            <div class="right">@pageNumber <span class="page-number"></span></div>
        </div>
    </div>
</body>

</html>
