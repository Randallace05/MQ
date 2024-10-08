<style>
    .header {
            padding: 0px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap:300px;
    }

    .topbar{
        background-color:black;
        height:30px;
            
    }

    .nav-links {
            display: flex;
            gap: 20px;
    }

    .nav-links a {
            color: black;
            text-decoration: none;
            font-family: 'Poppins';
            font-size: 40px;
            
    }
    .search-bar {
            padding: 5px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
    }

    hr{
        z-index: -1;
        margin-top: -10px;
    }

    /* Search Bar Styling */
    .search-bar {
        padding: 2px; /* Increased padding for better visibility */
        width: 100%; /* Full width to fit the container */
        max-width: 250px; /* Limit the max width */
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        padding-right: 35px; /* Space for the icon */
        background-image: url('https://cdn-icons-png.flaticon.com/512/622/622669.png'); /* Search icon */
        background-position: right 10px center; /* Position the icon */
        background-repeat: no-repeat; /* Ensure no repetition of the icon */
        background-size: 15px 15px; /* Adjust icon size */
    }

    .header img {
        width: 80px;
    }

    .nav-links {
        display: flex;
        gap: 20px;
    }

    /* Adjust search bar width for mobile */
    @media (max-width: 768px) {
        .search-bar {
            width: 100%; /* Full width on smaller screens */
            max-width: none; /* No max width limitation */
        }
    }
    

</style>

<!-- start include header -->
<?php include("../includes/header.php"); ?> 
<!-- end include header -->

<div class="topbar"></div>
<div class="header">
    <img src="uploads/bgMQ.png" alt="MO Kitchen Logo">
        <div class="nav-links">
            <a href="cart.php">Cart</a>
        </div>
    <input type="text" class="search-bar" placeholder="What are you looking for?">    
</div>
<hr>