<?php

// Notification classes

// NotifyWatchEnd: Shown when an auction ends
//                        to users who were watching but not participating
class NotifyWatchEnd{
    private $auctionID;
    private $itemName;
	private $startPrice;
    private $reservePrice;
    private $endDate;
    private $viewCount;
    private $hiBid;
	public function __construct(){
        $this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
		$this->startPrice = func_get_arg(2);
        $this->reservePrice = func_get_arg(3);
        $this->endDate = func_get_arg(4);
        $this->viewCount = func_get_arg(5);
		$this->hiBid = func_get_arg(6);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$startPrice = $this->startPrice;
		$reservePrice = $this->reservePrice;
		$endDate = $this->endDate;
		$viewCount = $this->viewCount;
		$hiBid = $this->hiBid;
        echo "<div class=\"alert alert-info\">
        <h3>Auction for <a href=\"auction.php?a=$auctionID\">$itemName</a> ended</h3>
        <p>The auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		ended on $endDate.</p>";
		if ($hiBid == -0.01){
			echo "<p>There were no bids in this auction.</p>
			<p>The start price was &pound;$startPrice</p>";
		} else {
			if ($hiBid<$reservePrice){
				echo "<p>The auction ended without sale.
				The highest bid was &pound;$hiBid,
				compared to the reserve price of &pound;$reservePrice</p>";
			} else {
				echo "<p>The winning bid was &pound;$hiBid</p>";
			}
		}
        echo "<p>The auction was viewed $viewCount times.</p></div>";
    }
}

// NotifyWatchCont: Shown when to users who are watching
//                         but not participating in an ongoing auction
class NotifyWatchCont{
    private $auctionID;
    private $itemName;
    private $endDate;
    private $viewCount;
    private $hiBid;
	public function __construct(){
        $this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->endDate = func_get_arg(2);
        $this->viewCount = func_get_arg(3);
		$this->hiBid = func_get_arg(4);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$endDate = $this->endDate;
		$viewCount = $this->viewCount;
		$hiBid = $this->hiBid;
        echo "<div class=\"alert alert-info\">
        <h3>Auction for <a href=\"auction.php?a=$auctionID\">$itemName</a> ongoing</h3>";
        if ($hiBid==-0.01){
            echo "<p>There are currently no bids
			for <a href=\"auction.php?a=$auctionID\">$itemName</a>.</p>";
        } else{
            echo "<p>The highest bid for <a href=\"auction.php?a=$auctionID\">
			$itemName</a> is &pound;$hiBid</p>";
        }
        echo "<p>The auction has been viewed $viewCount times so far.</p>
		<p>The auction is due to end on $endDate.</p></div>";
    }
}

// NotifyWon: Shown when to users when they win an auction
class NotifyWon{
    private $auctionID;
    private $itemName;
	private $sellerID; // TODO: use this to determine who is paid
    private $price;
	public function __construct(){
        $this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->sellerID = func_get_arg(2);
        $this->price = func_get_arg(3);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$sellerID = $this->sellerID;
		$price = $this->price;
        echo "<div class=\"alert alert-success\">
        <h3>Won auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a></h3>
		<p>You won the auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a>.</p>
		<p>Please <a href=\"#\">pay</a> the seller
		the agreed sum (&pound;$price) as soon as possible.</p></div>";
    }
}

// NotifyTooLow: Shown to users when an auction ends in which they were the
// highest bidder but didn't win as they bid below the reserve price
class NotifyTooLow{
    private $auctionID;
    private $itemName;
	private $reservePrice;
	private $endDate;
    private $userBid;
	public function __construct(){
        $this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->reservePrice = func_get_arg(2);
        $this->endDate = func_get_arg(3);
        $this->userBid = func_get_arg(4);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$reservePrice = $this->reservePrice;
		$endDate = $this->endDate;
		$userBid = $this->userBid;
        echo "<div class=\"alert alert-danger\">
        <h3>Bid for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		too low</h3>
		<p>The auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		ended on $endDate.
		Your bid was the highest, but was below the reserve price.</p>
		<p>You bid &pound;$userBid,
		compared to the reserve price of &pound;$reservePrice</p></div>";
    }
}

// NotifyLost: Shown to users when they lose an auction
class NotifyLost{
    private $auctionID;
    private $itemName;
	private $reservePrice;
	private $endDate;
    private $userBid;
	private $hiBid;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->reservePrice = func_get_arg(2);
        $this->endDate = func_get_arg(3);
        $this->userBid = func_get_arg(4);
		$this->hiBid = func_get_arg(5);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$reservePrice = $this->reservePrice;
		$endDate = $this->endDate;
		$userBid = $this->userBid;
        $hiBid = $this->hiBid;
        echo "<div class=\"alert alert-danger\">
        <h3>Lost auction for <a href=\"auction.php?a=$auctionID\">$itemName</a></h3>";
        echo "<p>You lost the auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a></p>";
		if ($hiBid<$reservePrice){
			echo "<p>The auction ended on $endDate without sale.
			The highest bid was	&pound;$hiBid,
			compared to the reserve price of &pound;$reservePrice</p>";
		} else {
			echo "<p>The auction ended on $endDate, with a winning bid of &pound;$hiBid</p>";
		}
		echo "<p>You bid &pound;$userBid</p></div>";
    }
}

// NotifyWinning: Shown to users when their bid is the highest in an ongoing auction
class NotifyWinning{
    private $auctionID;
    private $itemName;
	private $endDate;
    private $userBid;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->endDate = func_get_arg(2);
        $this->userBid = func_get_arg(3);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$endDate = $this->endDate;
		$userBid = $this->userBid;
        echo "<div class=\"alert alert-success\">
        <h3>Winning auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a></h3>
		<p>You are winning the auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		Your current bid is &pound;$userBid</p>
		<p>The auction is due to end on $endDate.</p></div>";
    }
}

// NotifyOutbid: Shown to users when their bid is not the highest in an ongoing auction
class NotifyOutbid{
	private $auctionID;
    private $itemName;
	private $endDate;
    private $userBid;
	private $hiBid;
	public function __construct(){
		// most attributes passed in by script
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->endDate = func_get_arg(2);
        $this->userBid = func_get_arg(3);
		$this->hiBid = func_get_arg(4);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$endDate = $this->endDate;
		$userBid = $this->userBid;
        $hiBid = $this->hiBid;
        echo "<div class=\"alert alert-warning\">
        <h3>Outbid for
		for <a href=\"auction.php?a=$auctionID\">$itemName</a></h3>
		<p>You've  been outbid in the auction
		for <a href=\"auction.php?a=$auctionID\">$itemName</a></p>
		<p>Your current bid is &pound;$userBid,
		compared to the highest bid of &pound;$hiBid</p>
		<p>You have until $endDate to place another bid.</p></div>";
    }
}

// NotifySold: Shown to users when they have sold an item
class NotifySold{
	private $auctionID;
    private $itemName;
	private $winnerID;
	private $hiBid;
	private $buyerAddress;
	public function __construct(){
		// most attributes passed in by script
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->winnerID = func_get_arg(2);
        $this->hiBid = func_get_arg(3);
		// TODO: Get buyer's address
		$this->buyerAddress = "the buyer's address";
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$winnerID = $this->winnerID;
		$hiBid = $this->hiBid;
		$buyerAddress = $this->buyerAddress;
        echo "<div class=\"alert alert-success\">
        <h3><a href=\"auction.php?a=$auctionID\">$itemName</a> sold</h3>
		<p>You sold <a href=\"auction.php?a=$auctionID\">$itemName</a>
		for &pound;$hiBid</p>
		<p>Please ship the item to $buyerAddress as soon as possible</p></div>";
    }
}

// NotifyTooHigh: Shown to users when their auction ended
//                with bids which were all below the reserve price
class NotifyTooHigh{
	private $auctionID;
    private $itemName;
	private $reservePrice;
	private $endDate;
	private $hiBid;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->reservePrice = func_get_arg(2);
        $this->endDate = func_get_arg(3);
        $this->hiBid = func_get_arg(4);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$reservePrice = $this->reservePrice;
		$endDate = $this->endDate;
		$hiBid = $this->hiBid;
        echo "<div class=\"alert alert-danger\">
        <h3>All bids for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		below reserve</h3>
		<p>The auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		ended on $endDate with no bids above the reserve price.</p>
		<p>The highest bid was &pound;$hiBid,
		compared to the reserve price of &pound;$reservePrice</p></div>";
    }
}

// NotifyNoBidEver: Shown to users when their auction ended with no bids at all
class NotifyNoBidEver{
	private $auctionID;
    private $itemName;
	private $startPrice;
	private $startDate;
	private $endDate;
	private $viewCount;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->startPrice = func_get_arg(2);
        $this->startDate = func_get_arg(3);
        $this->endDate = func_get_arg(4);
        $this->viewCount = func_get_arg(5);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$startPrice = $this->startPrice;
		$startDate = $this->startDate;
		$endDate = $this->endDate;
		$viewCount = $this->viewCount;
        echo "<div class=\"alert alert-danger\">
        <h3>No bids for <a href=\"auction.php?a=$auctionID\">$itemName</a></h3>
		<p>The auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		ended with no bids at all.</p>
		<p>The auction was viewed $viewCount times
		between $startDate and $endDate.</p>
		<p>The start price you set was &pound;$startPrice</p></div>";
    }
}

// NotifySelling: Shown to users with ongoing auctions
//               which have received a bid equal to or above the reserve price
class NotifySelling{
	private $auctionID;
    private $itemName;
	private $endDate;
	private $hiBid;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->endDate = func_get_arg(2);
        $this->hiBid = func_get_arg(3);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$endDate = $this->endDate;
		$hiBid = $this->hiBid;
        echo "<div class=\"alert alert-success\">
        <h3>Auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		ongoing</h3>
		<p>The auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		is ongoing. There <em>are</em> bids above the reserve price.</p>
		<p>The highest bid so far is $hiBid</p>
		<p>The auction will continue until $endDate.</p></div>";
    }
}

// NotifyWaiting: Shown to users with ongoing auctions
//                for which all bids are currently below the reserve price
class NotifyWaiting{
	private $auctionID;
    private $itemName;
	private $reservePrice;
	private $endDate;
	private $hiBid;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->reservePrice = func_get_arg(2);
        $this->endDate = func_get_arg(3);
        $this->hiBid = func_get_arg(4);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$reservePrice = $this->reservePrice;
		$endDate = $this->endDate;
		$hiBid = $this->hiBid;
        echo "<div class=\"alert alert-info\">
        <h3>Auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		ongoing</h3>
		<p>The auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		is ongoing. All bids so far are <em>below</em> the reserve price of $reservePrice</p>
		<p>The highest bid so far is $hiBid</p>
		<p>The auction will continue until $endDate.</p></div>";
    }
}

// NotifyNoBidYet: Shown to users with ongoing auctions
//                for which there are as yet no bids
class NotifyNoBidYet{
	private $auctionID;
    private $itemName;
	private $startPrice;
	private $startDate;
	private $endDate;
	private $viewCount;
	public function __construct(){
		$this->auctionID = func_get_arg(0);
        $this->itemName = func_get_arg(1);
        $this->startPrice = func_get_arg(2);
        $this->startDate = func_get_arg(3);
        $this->endDate = func_get_arg(4);
        $this->viewCount = func_get_arg(5);
	}
    
    
    public function show(){
		$auctionID = $this->auctionID;
		$itemName = $this->itemName;
		$startPrice = $this->startPrice;
		$startDate = $this->startDate;
		$endDate = $this->endDate;
		$viewCount = $this->viewCount;
        echo "<div class=\"alert alert-warning\">
        <h3>No bids for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		yet</h3>
		<p>The auction for <a href=\"auction.php?a=$auctionID\">$itemName</a>
		is ongoing. There have been no bids so far.</p>
		<p>The start price you have set is $startPrice</p>
		<p>The auction has been viewed $viewCount times.</p>
		<p>The auction has been running since $startDate
		and will continue until $endDate.</p></div>";
    }
}

?>
