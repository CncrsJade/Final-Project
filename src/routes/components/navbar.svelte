<script>
  import '@fortawesome/fontawesome-free/css/all.min.css';
  import { isAuthenticated } from '$lib/stores/auth';
  import { goto } from '$app/navigation';

  function handleLogout() {
    $isAuthenticated = false;
    goto('/login');
  }
</script>


<style>
 .sidebar {
   position: fixed;
   top: 0;
   left: 0;
   width: 250px;
   height: 100vh;
   background-color: #2c2f33;
   color: #fff;
   display: flex;
   flex-direction: column;
   padding: 20px;
   box-sizing: border-box;
   z-index: 10;
 }

 .menu-item {
   display: flex;
   align-items: center;
   padding: 10px 0;
   cursor: pointer;
   color: #b9bbbe;
   text-decoration: none;
 }

 .menu-item:hover {
   color: #fff;
 }

 .menu-item.logout {
   color: #e74c3c;
 }

 .icon {
   margin-right: 10px;
 }
</style>
<div class="sidebar">
 <a class="menu-item" href="/dashboard">
     <span class="icon">
         <i class="fa-solid fa-chart-simple"></i>
     </span>
   Dashboard
 </a>
 <a class="menu-item" href="/users">
   <span class="icon">
     <i class="fa-solid fa-user"></i>
   </span>
   Users
 </a>
 <a class="menu-item" href="/products">
   <span class="icon">
     <i class="fa-solid fa-basket-shopping"></i>    
 </span>
 Products
 </a>
 <div class="flex-grow"></div>
 <button class="menu-item logout" on:click={handleLogout}>
   <span class="icon">
     <i class="fa-solid fa-sign-out-alt"></i>
 </span>
   Logout
 </button>
</div>
