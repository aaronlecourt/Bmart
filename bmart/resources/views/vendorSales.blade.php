@extends('layouts.app2')
@section('title', 'Product Sales Report')
@section('content')
<div id="section-cont">
    @if(session()->has('message'))
    <div class="bg-success alert rounded-3">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        {{ session()->get('message') }}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="bg-danger alert rounded-3">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        {{ session()->get('error') }}
    </div>
    @endif
<h3 style="font-weight:600;">Hello {{Auth()->user()->name}}!</h3>
<h6>Here is an overview of your product sales!</h6>
<br>
<div class="row">
    <div class="col-md-2 col-sm-12 p-3">
        <h5 style="font-weight:600; text-align:center">Product Orders Overview</h5>
        <p style="text-align:center; color:gray;">Product Orders are shown regardless of its order status</p>
        <canvas id="pieChart"></canvas>
    </div>
    <div class="col-md-10 col-sm-12 ">
        <div class="px-0 mb-2" style="font-weight:600">
            <form action="{{ route('vendor.sales') }}" method="POST" class="w-100">
                @csrf
            <div class="row p-3 w-100 d-flex justify-content-between align-items-end">
                
                    <div class="col-md-2">Product Category:
                        <select name="category_filter" id="category_filter" class="form-select">
                            <option value="" selected hidden>
                                {{ $categories->firstWhere('id', $categoryFilter)->category_name ?? '' }}
                            </option>                    
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">Order Status:
                        <select name="status_filter" id="status_filter" class="form-select">
                            <option value="" selected hidden>{{$statusFilter}}</option>
                            <option value="pending"><div class="bg-warning text-white px-2 rounded-pill">pending</div></option>
                            <option value="confirmed">confirmed</option>
                            <option value="for approval">for approval</option>
                            <option value="request rejected">request rejected</option>
                            <option value="cancelled">cancelled</option>
                            <option value="shipped">shipped</option>
                            <option value="delivered">delivered</option>
                        </select>
                    </div>
                    <div class="col-md-1">Month:
                        <select class="form-select" id="month_filter" name="month_filter">
                            <option value="" selected hidden>
                                @if ($monthFilter == 0) @else{{ \Carbon\Carbon::createFromFormat('m', sprintf('%02d', $monthFilter))->format('F') }}@endif
                            </option>                    <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="col-md-1">Day:
                        <select class="form-select" id="day_filter" name="day_filter">
                            <option value="" hidden selected>{{$dayFilter}}</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                        </select>
                    </div>
                    <div class="col-md-1">Year:
                        <select class="form-select" id="year_filter" name="year_filter">
                            <option value="" hidden selected>{{$yearFilter}}</option>
                            <option value="1999">1999</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="col-md-1">Sort By:
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="" hidden selected>{{$sortBy}}</option>
                            <option value="product_sort">Product</option>
                            <option value="date_sort">Date</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-secondary w-100 mt-1" type="submit" style="font-weight:600" name="apply_filter">Apply Filters</button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" style="font-weight:600" class="btn btn-warning w-100 mt-1" onclick="window.location.href='{{ route('vendor.sales.clear-filters') }}'">Clear Filters</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 mt-1" id="generateReportBtn" name="generate" type="submit" style="font-weight:600">Generate Report</button>            
                    </div>
                </form>
            </div>
        </div>
        <div class="rounded-3 container-fluid p-3" style="border: 1px solid rgba(0,0,0,0.2)">
            <table class="table table-hover" id="salesTable">
                <thead>
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>Product Name</th>
                        <th>Product Category</th>
                        <th>Status</th>
                        <th>No. of items</th>
                        <th>Total Earnings</th>
                        <th>Date (Month/Day/Year)</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="6" style="font-weight:600; color:gray;">No records found.</td>
                        </tr>
                    @else
                        @foreach($products as $product)
                        <tr>
                            {{-- <td>#{{$product->product_id}}</td> --}}
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->product_category}}</td>
                            @if($product->status == 'pending')
                                <td><span class="bg-warning text-white px-2 rounded-pill">{{$product->status}}</span></td>
                            @elseif($product->status == 'confirmed')
                                <td><span class="bg-success text-white px-2 rounded-pill">{{$product->status}}</span></td>
                            @elseif($product->status == 'for approval')
                                <td><span class="bg-secondary text-white px-2 rounded-pill">{{$product->status}}</span></td>
                            @elseif($product->status == 'request rejected')
                                <td><span class="border border-secondary text-secondary px-2 rounded-pill">{{$product->status}}</span></td>
                            @elseif($product->status == 'cancelled')
                                <td><span class="bg-danger text-white px-2 rounded-pill">{{$product->status}}</span></td>
                            @elseif($product->status == 'shipped')
                                <td><span class="text-white px-2 rounded-pill" style="background-color:orange">{{$product->status}}</span></td>
                            @elseif($product->status == 'delivered')
                                <td><span class="bg-primary text-white px-2 rounded-pill">{{$product->status}}</span></td>
        
                            @endif
                            <td>{{$product->total_quantity}}</td>
                            <td>{{$product->total_earnings}}</td>
                            @php
                                $totalSales += intval($product->total_earnings) // Add current earnings to total sales
                            @endphp
                            <td>
                                {{-- {{dd($product->month, $product->day, $product->year);}} --}}
                                {{ \Carbon\Carbon::createFromFormat('m', sprintf('%02d', $product->month))->format('F') }}
                                {{$product->day}}, {{$product->year}}
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4"><h6 style="font-weight:600; text-align:right">Total Sales:</h6></td>
                            <td colspan="2"><h6 style="font-weight:600; text-align:left">{{number_format($totalSales, 2)}}</h6></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{$products->links('pagination::bootstrap-5')}}
            @if ($products->total() < 10 && $products->total() > 0)
            <small style="font-weight:600; color:gray;">Showing {{ $totalResults }} result(s)</small>
            @endif
        
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  // Retrieve the data from the query results
  var labels = [];
  var data = [];
  var colors = <?php echo json_encode($colors); ?>;

  // Assuming the query results are stored in the 'results' variable
  @foreach ($results as $result)
    labels.push('{{ $result->product_name }}');
    data.push({{ $result->order_count }});
  @endforeach

  // Generate random colors if the number of data points exceeds the number of custom colors
    if (data.length > colors.length) {
    for (var i = colors.length; i < data.length; i++) {
        var randomColor = Math.floor(Math.random() * 16777215).toString(16);
        colors.push(randomColor);
    }
    }

  // Create the pie chart
  var ctx = document.getElementById('pieChart').getContext('2d');
  var pieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: colors,
        hoverBackgroundColor: colors
      }]
    },
    options: {
      responsive: true
    }
  });
</script>
@endsection