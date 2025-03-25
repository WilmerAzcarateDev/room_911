<style>
.header-container {
    width: 100%;
    font-family: sans-serif;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    padding: 10px 0;
    margin: 0;
    
    display: grid;
    grid-template-columns: repeat(2, 1fr);
}

.header-separator {
    border-top: 2px solid #999;
    margin-top: 5px;
}
</style>
  
<div class="header-container">
    <span>Access Report</span>
    <span>{{ $date }}</span>
    <span>User:</span>
    <span>{{ $user->name }} {{ $user->last_name }}</span>
    <span>Document: </span>
    <span>{{ $user->document }}</span>
   
</div>
<hr class="header-separator">
