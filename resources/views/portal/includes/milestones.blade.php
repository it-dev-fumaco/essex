<section class="timeline-section timeline p-5">
    <div class="info bg-image">
        <img class="w-75" src="{{ asset('/storage/img/company_logo.png') }}" />
        <h5>Historical Milestones</h5>
        <p>The Art of Science & Lighting</p>
        <p>
            <a href="/historical_milestones">Learn more &gt;</a>
        </p>
    </div>

    <ol>
        @foreach($milestones as $milestone)
            <li>
                <div>
                    <time>{{ Carbon\Carbon::parse($milestone->created_at)->format('M-Y') }}</time>
                    <b>{{ $milestone->title }}</b> - <small>{{ strip_tags(substr($milestone->content, 0, 100)) }}...</small>
                </div>
            </li>
        @endforeach
        <li></li>
    </ol>

</section>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap");

    :root {
        --white: #fff;
        --black: #323135;
        --crystal: #a8dadd;
        --columbia-blue: #cee9e4;
        --midnight-green: #01565b;
        --main-color: #0875C1;
        --accent: rgba(8, 117, 193, .15);
        --yellow: rgba(226, 243, 40, 0.849);
        --timeline-gradient: rgba(206, 233, 228, 1) 0%, rgba(206, 233, 228, 1) 50%,
            rgba(206, 233, 228, 0) 100%;
    }

    *,
    *::before,
    *::after {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .timeline-section button {
        background: transparent;
        border: none;
        cursor: pointer;
        outline: none;
    }

    .timeline-section a {
        color: inherit;
    }

    .timeline-section img {
        max-width: 100%;
        height: auto;
    }

    .timeline-section {
        font-family: 'Poppins-Regular' !important;
        background: #fff;
        color: var(--black);
        margin-bottom: 50px;
    }

    /* .section SECTION
–––––––––––––––––––––––––––––––––––––––––––––––––– */

    .section {
        padding: 50px 0;
    }

    .section .container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }

    .section h1 {
        font-size: 2.5rem;
        line-height: 1.25;
    }

    .section h2 {
        font-size: 1.3rem;
    }

    /* TIMELINE
–––––––––––––––––––––––––––––––––––––––––––––––––– */

    .timeline {
        position: relative;
        white-space: nowrap;
        /* max-width: 1400px; */
        padding: 0 10px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 320px auto;
        grid-gap: 20px;
    }

    .timeline::before,
    .timeline::after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 30px;
        width: 100px;
        z-index: 2;
    }

    .timeline::after {
        right: 0;
        /* background: linear-gradient(270deg, var(--timeline-gradient)); */
    }

    .timeline::before {
        left: 340px;
        /* background: linear-gradient(90deg, var(--timeline-gradient)); */
    }

    .timeline .info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 20px 40px;
        color: var(--white);
        white-space: normal;
        border-radius: 10px;
    }

    .bg-image{
        border-radius: 10px;
        background-image: url("{{ asset('storage/img/featured/3.jpg') }}");
        background-color: #cccccc;
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .timeline .info img {
        margin-bottom: 20px;
    }

    .timeline .info p {
        margin-top: 10px;
        color: var(--crystal);
    }

    .timeline .info a {
        text-decoration: none;
    }

    .timeline ol::-webkit-scrollbar {
        height: 12px;
    }

    .timeline ol::-webkit-scrollbar-thumb,
    .timeline ol::-webkit-scrollbar-track {
        border-radius: 92px;
    }

    .timeline ol::-webkit-scrollbar-thumb {
        background: var(--main-color);
    }

    .timeline ol::-webkit-scrollbar-track {
        background: var(--yellow);
    }

    .timeline ol {
        font-size: 0;
        padding: 250px 0;
        transition: all 1s;
        overflow-x: scroll;
        scroll-snap-type: x mandatory;
        scrollbar-color: var(--yellow) var(--main-color);
        /* scrollbar-color: var(--yellow) var(--midnight-green); */
    }

    .timeline ol li {
        position: relative;
        display: inline-block;
        list-style-type: none;
        width: 160px;
        height: 5px;
        background: var(--accent);
        scroll-snap-align: start;
    }

    .timeline ol li:last-child {
        width: 340px;
    }

    .timeline ol li:not(:first-child) {
        margin-left: 14px;
    }

    .timeline ol li:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 50%;
        left: calc(100% + 1px);
        bottom: 0;
        width: 16px;
        height: 16px;
        transform: translateY(-50%);
        border-radius: 50%;
        background: var(--main-color);
        z-index: 1;
    }

    .timeline ol li div {
        position: absolute;
        left: calc(100% + 7px);
        width: 280px;
        padding: 15px;
        font-size: .9rem;
        white-space: normal;
        color: var(--black);
        background: var(--accent);
        border-radius: 0 10px 10px 10px;
    }

    .timeline ol li div::before {
        content: "";
        position: absolute;
        top: 100%;
        left: 0;
        width: 0;
        height: 0;
        border-style: solid;
    }

    .timeline ol li:nth-child(odd) div {
        top: -16px;
        transform: translateY(-100%);
        border-radius: 10px 10px 10px 0;
    }

    .timeline ol li:nth-child(odd) div::before {
        top: 100%;
        border-width: 8px 8px 0 0;
        border-color: var(--white) transparent transparent transparent;
    }

    .timeline ol li:nth-child(even) div {
        top: calc(100% + 16px);
    }

    .timeline ol li:nth-child(even) div::before {
        top: -8px;
        border-width: 8px 0 0 8px;
        border-color: transparent transparent transparent var(--white);
    }

    .timeline time {
        display: block;
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 8px;
        color: var(--main-color);
    }

    /* GENERAL MEDIA QUERIES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    @media screen and (max-width: 800px) {
        .timeline {
            display: block;
        }

        .timeline::before,
        .timeline::after {
            width: 50px;
        }

        .timeline::before {
            left: 0;
        }

        .timeline .info {
            display: none;
        }
    }

    /* FOOTER STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
    .page-footer {
        position: fixed;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        padding: 5px;
        z-index: 2;
        color: var(--black);
        background: var(--columbia-blue);
    }

    .page-footer a {
        display: flex;
        margin-left: 4px;
    }
</style>

<script>
    // VARIABLES
    const elH = document.querySelectorAll(".timeline li > div");

    // START
    window.addEventListener("load", init);

    function init() {
    setEqualHeights(elH);
    }

    // SET EQUAL HEIGHTS
    function setEqualHeights(el) {
    let counter = 0;
    for (let i = 0; i < el.length; i++) {
        const singleHeight = el[i].offsetHeight;

        if (counter < singleHeight) {
        counter = singleHeight;
        }
    }

    for (let i = 0; i < el.length; i++) {
        el[i].style.height = `${counter}px`;
    }
    }

</script>