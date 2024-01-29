<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختيار العملية</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            height: 900px;
        }

        header {
            background: url('http://www.autodatz.com/wp-content/uploads/2017/05/Old-Car-Wallpapers-Hd-36-with-Old-Car-Wallpapers-Hd.jpg');
            text-align: center;
            width: 100%;
            height: auto;
            background-size: cover;
            background-attachment: fixed;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 85% 85% / 30%;
        }

        header .overlay {
            width: 100%;
            height: 100%;
            padding: 50px;
            color: #FFF;
            text-shadow: 1px 1px 1px #333;
            background-image: linear-gradient(135deg, #9f05ff69 10%, #fd5e086b 100%);

        }

        h1 {
            font-family: 'Dancing Script', cursive;
            font-size: 80px;
            margin-bottom: 30px;
        }

        h3,
        p {
            font-family: 'Open Sans', sans-serif;
            margin-bottom: 30px;
        }

        .card {
            --blur: 16px;
            --size: clamp(300px, 50vmin, 600px);
            width: var(--size);
            aspect-ratio: 4 / 3;
            position: relative;
            border-radius: 2rem;
            overflow: hidden;
            color: #000;
            transform: translateZ(0);
        }

        .card__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(calc(1 + (var(--hover, 0) * 0.25))) rotate(calc(var(--hover, 0) * -5deg));
            transition: transform 0.2s;
        }

        .card__footer {
            padding: 0 1.5rem;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: red;
            display: grid;
            grid-template-row: auto auto;
            gap: 0.5ch;
            background: hsl(0 0% 100% / 0.5);
            backdrop-filter: blur(var(--blur));
            height: 30%;
            align-content: center;
        }

        .card__action {
            position: absolute;
            aspect-ratio: 1;
            width: calc(var(--size) * 0.15);
            bottom: 30%;
            right: 1.5rem;
            transform: translateY(50%) translateY(calc((var(--size) * 0.4))) translateY(calc(var(--hover, 0) * (var(--size) * -0.4)));
            background: purple;
            display: grid;
            place-items: center;
            border-radius: 0.5rem;
            background: hsl(0 0% 100% / 0.5);
            /*   backdrop-filter: blur(calc(var(--blur) * 0.5)); */
            transition: transform 0.2s;
        }

        .card__action svg {
            aspect-ratio: 1;
            width: 50%;
        }

        .card__footer span:nth-of-type(1) {
            font-size: calc(var(--size) * 0.065);
        }

        .card__footer span:nth-of-type(2) {
            font-size: calc(var(--size) * 0.035);
        }

        .card:is(:hover, :focus-visible) {
            --hover: 1;
        }
    </style>
</head>

<body>
    <div style="display: flex; flex-direction: column;">
        <header>
            <div class="overlay">
                <h1>Simply The Best</h1>
                <h3>Reasons for Choosing US</h3>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Vero nostrum quis, odio veniam itaque ullam
                    debitis qui magnam consequatur ab. Vero nostrum quis, odio veniam itaque ullam debitis qui magnam
                    consequatur ab.</p>
                <br>
                <div style="display: flex; justify-content: space-around;">
                    <a href="{{ route('runDash') }}" class="card">
                        <img src="https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHRyYW5zcG9ydHxlbnwwfHwwfHx8MA%3D%3D"
                            alt="balloon with an emoji face" class="card__img">
                        <span class="card__footer">
                            <span>ادارة التشغيل</span>
                        </span>
                        <span class="card__action">
                            <svg viewBox="0 0 448 512" title="play">
                                <path
                                    d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                            </svg>
                        </span>
                    </a>

                    <a href="{{ route('getEmployee') }}" class="card">
                        <img src="https://plus.unsplash.com/premium_photo-1678917191085-048e25c687fd?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8ZW1wbG95ZWVzfGVufDB8fDB8fHww"
                            alt="balloon with an emoji face" class="card__img">
                        <span class="card__footer">
                            <span>ادارة الموظفين</span>
                        </span>
                        <span class="card__action">
                            <svg viewBox="0 0 448 512" title="play">
                                <path
                                    d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                            </svg>
                        </span>
                    </a>
                    <a href="{{ route('FinancialManagement') }}" class="card">
                        <img src="https://plus.unsplash.com/premium_photo-1679496828905-1f6d9ac5721a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YWNjb3VudGluZ3xlbnwwfHwwfHx8MA%3D%3D"
                            alt="balloon with an emoji face" class="card__img">
                        <span class="card__footer">
                            <span>ادارة المالية</span>
                        </span>
                        <span class="card__action">
                            <svg viewBox="0 0 448 512" title="play">
                                <path
                                    d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </header>
    </div>

</body>

</html>
