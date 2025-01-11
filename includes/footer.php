<style>
    /* Footer */
    footer {
        background-color: black;
        color: white;
        padding: 20px 20px; /* Reduced padding */
        display: flex;
        flex-wrap: wrap;
        align-items: center; /* Center content vertically */
        justify-content: space-between; /* Spread out the columns */
        font-size: 12px; /* Global font size for footer */
    }

    footer .column {
        flex: 1 1 150px; /* Adjusted width for smaller column size */
        padding: 10px; /* Smaller padding */
        box-sizing: border-box;
        margin-left: 100px;
    }

    footer h4 {
        font-size: 16px; /* Smaller heading font size */
        margin-bottom: 10px; /* Adjust margin below headings */
    }

    footer p, footer a {
        font-size: 12px; /* Smaller paragraph and link font size */
        margin-bottom: 5px; /* Spacing between elements */
    }

    footer input[type="email"] {
        padding: 8px; /* Smaller input padding */
        border-radius: 5px;
        border: none;
        margin-right: 10px;
        width: 180px; /* Smaller input width */
        font-size: 12px; /* Smaller input font size */
    }

    footer button {
        padding: 8px; /* Reduced button padding */
        border: none;
        background-color: red;
        color: white;
        border-radius: 5px;
        font-size: 12px; /* Smaller button font size */
        cursor: pointer;
    }

    footer a {
        color: white;
        text-decoration: none;
    }

    footer a:hover {
        text-decoration: underline;
    }

    /* Media Queries for Mobile */
    @media (max-width: 768px) {
        footer {
            flex-direction: column;
            align-items: flex-start;
        }

        footer .column {
            flex: 1 1 100%;
            margin-bottom: 15px;
        }
    }

    
    .wrapper {
    display: inline-flex;
    list-style: none;
    height: 120px;
    width: 100%;
    padding-top: 40px;
    font-family: "Poppins", sans-serif;
    justify-content: center;
    }

    .wrapper .icon {
    position: relative;
    background: #fff;
    border-radius: 50%;
    margin: 10px;
    width: 40px;
    height: 40px;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .icon{
        color: black;
    }

    .wrapper .tooltip {
    position: absolute;
    top: 0;
    font-size: 14px;
    background: #fff;
    color: #fff;
    padding: 5px 8px;
    border-radius: 5px;
    box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .wrapper .tooltip::before {
    position: absolute;
    content: "";
    height: 8px;
    width: 8px;
    background: #fff;
    bottom: -3px;
    left: 50%;
    transform: translate(-50%) rotate(45deg);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .wrapper .icon:hover .tooltip {
    top: -45px;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    }

    .wrapper .icon:hover span,
    .wrapper .icon:hover .tooltip {
    text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
    }

    .wrapper .facebook:hover,
    .wrapper .facebook:hover .tooltip,
    .wrapper .facebook:hover .tooltip::before {
    background: #1877f2;
    color: #fff;
    }

    .wrapper .twitter:hover,
    .wrapper .twitter:hover .tooltip,
    .wrapper .twitter:hover .tooltip::before {
    background:rgb(37, 37, 37);
    color: #fff;
    }

    .wrapper .instagram:hover,
    .wrapper .instagram:hover .tooltip,
    .wrapper .instagram:hover .tooltip::before {
    background: #e4405f;
    color: #fff;
    }
</style>

<footer>
    <div class="column">
        <ul class="wrapper">

        <a href="https://www.facebook.com/mqkitchen.main" target="_blank">
            <li class="icon facebook">
                <span class="tooltip">Facebook</span>
                <svg
                viewBox="0 0 320 512"
                height="1.2em"
                fill="currentColor"
                xmlns="http://www.w3.org/2000/svg"
                >
                <path
                    d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                ></path>
                </svg>
            </li>
        </a>

        <a href="https://www.tiktok.com/@mqkitchen.main" target="_blank">
            <li class="icon twitter">
                <span class="tooltip">Tiktok</span>
                <i class="fa-brands fa-tiktok"></i>
            </li>
        </a>
        <a href="https://www.instagram.com/mqkitchen.main/" target="_blank">
            <li class="icon instagram">
                <span class="tooltip">Instagram</span>
                <svg
                xmlns="http://www.w3.org/2000/svg"
                height="1.2em"
                fill="currentColor"
                class="bi bi-instagram"
                viewBox="0 0 16 16"
                >
                <path
                    d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"
                ></path>
                </svg>
            </li>
        </a>

        </ul>
    </div>
    <div class="column">
        <h4>Support</h4>
        <p>Tanza, Cavite</p>
        <p>Email: MQKitchen@gmail.com</p>
    </div>
    <div class="column">
        <h4>Account</h4>
        <p><a href="#" style="color: white;">My Account</a></p>
        <p><a href="#" style="color: white;">Login / Register</a></p>
        <p><a href="#" style="color: white;">Shop</a></p>
    </div>
    <div class="column">
        <h4>Quick Link</h4>
        <p><a href="#" style="color: white;">Terms Of Use</a></p>
        <p><a href="#" style="color: white;">FAQ</a></p>
        <p><a href="#" style="color: white;">Contact</a></p>
    </div>

    <!-- <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div> -->
</footer>