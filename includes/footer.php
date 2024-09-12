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


</style>

<footer>
    <div class="column">
        <h4>Exclusive</h4>
        <p>Get 10% off your first order</p>
        <input type="email" placeholder="Enter your email">
        <button>Subscribe</button>
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
</footer>