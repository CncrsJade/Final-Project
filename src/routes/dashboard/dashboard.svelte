<script lang="ts">
  import { onMount } from 'svelte';
  import Chart from 'chart.js/auto';
  import type { Chart as ChartType } from 'chart.js';

  let salesToday = 0.00;
  let totalProducts = 0;
  let categories = 0;
  let lowStockItems = 0;

  let salesChart: ChartType | undefined;

  // Function to initialize/refresh chart
  function initializeChart() {
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;
    
    const ctx = (canvas as HTMLCanvasElement).getContext('2d');
    if (!ctx) return;
    
    if (salesChart) {
      salesChart.destroy(); // Destroy existing chart before creating new one
    }
    
    salesChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        datasets: [{
          label: 'Sales',
          data: [120, 150, 180, 200, 170, 220, 250], // Example data
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 2,
          fill: false
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Weekly Sales'
          }
        }
      }
    });
  }

  // Function to refresh data
  function refreshData() {
    // Here you would typically fetch new data from your API
    salesToday = 0.00;
    totalProducts = 0;
    lowStockItems = 0;
    
    // Refresh the chart
    initializeChart();
  }

  onMount(() => {
    initializeChart();
  });
</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

  .dashboard {
    padding: 2rem;
    display: flex;
    box-sizing: border-box;
    width: 100%;
  }
  
  .content {
    flex-grow: 1;
    width: 100%;
  }
  
  .button-group {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }
  .card {
    display: inline-block;
    width: 100%;
    max-width: 200px;
    margin: 10px;
    padding: 20px;
    border-radius: 5px;
    color: white;
    text-align: center;
  }
  .sales { background-color: #007bff; }
  .products { background-color: #28a745; }
  .low-stock { background-color: #ffc107; }
  button {
    margin-right: 10px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
  }
  .refresh { background-color: #28a745; }
  .table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  .table th, .table td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  .table th {
    background-color: #f2f2f2;
  }
  .card-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
  }
  .dashboard h1 {
    font-family: 'Poppins', sans-serif;
    font-size: 2.5rem;
    font-weight: bold;
  }
  .poppins-bold {
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
  }
  @media (max-width: 768px) {
    .button-group {
      justify-content: center;
    }
    .card {
      width: 100%;
      max-width: none;
    }
    .table th, .table td {
      padding: 6px;
    }
  }
</style>

<div class="dashboard">
  <div class="content">
    <h1>Dashboard</h1>
    <div class="button-group">
      <button 
        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 flex items-center gap-2"
        on:click={refreshData}
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
        </svg>
        Refresh
      </button>
    </div>
    <div class="card-container">
      <div class="card sales w-full">
        <h2>Today's Sales</h2>
        <p>₱{salesToday.toFixed(2)}</p>
      </div>
      <div class="card products w-full">
        <h2>Total Products</h2>
        <p>{totalProducts}</p>
      </div>
      <div class="card low-stock w-full">
        <h2>Low Stock Items</h2>
        <p>{lowStockItems}</p>
      </div>
    </div>
    <div>
      <canvas id="salesChart" width="400" height="200"></canvas>
    </div>
  </div>
</div>