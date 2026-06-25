<!DOCTYPE html>
<html>
<head>
    <title>Track Point Timeline</title>

    <style>
        .track-card {
            border: 1px solid #cfcfcf;
            border-radius: 10px;
            overflow: hidden;
            max-width: 350px;
            background: #f5f5f5;
        }

        .track-header {
            background: #43b39d;
            color: #fff;
            font-weight: bold;
            padding: 15px 20px;
            font-size: 24px;
            text-transform: uppercase;
        }

        .timeline {
            position: relative;
            padding: 20px 20px 20px 60px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 35px;
            top: 20px;
            bottom: 20px;
            width: 2px;
            background: #b8b8b8;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -36px;
            top: 5px;
            width: 18px;
            height: 18px;
            background: #b8b8b8;
            border-radius: 50%;
        }

        .location-title {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 3px;
        }

        .location {
            color: #666;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .datetime {
            color: #666;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .status {
            font-size: 14px;
            font-weight: 700;
            color: #333;
        }

        .status span {
            font-weight: 600;
        }
    </style>
</head>
<body>
    @if (count($trackingPoint) > 0)
        <div class="timeline">
            @foreach ($trackingPoint as $item)
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="location-title">{{$item->tracking_point}}</div>
                    {{-- <div class="location">Cebu Philippines</div> --}}
                    <div class="datetime">{{$item->arrival_date}}</div>
                    <div class="status">Status: <span>{{$item->DeliveryStatus['description']??""}}</span></div>
                </div>
            @endforeach
        </div>
    @else
        <center><h3>No Shipment Tracking.</h3></center>
    @endif
</body>
</html>