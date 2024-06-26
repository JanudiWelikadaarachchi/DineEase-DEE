<?php
class Customer
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }



    public function getReservation($user_id, $limit = 10, $offset = 0)
    {
        $this->db->query('SELECT * FROM reservation WHERE customerID = :user_id ORDER BY date ASC LIMIT :offset, :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getReservationDetailsByID($reservationID)
    {

        $this->db->query('SELECT * FROM reservation WHERE reservationID = :reservationID');
        $this->db->bind(':reservationID', $reservationID);
        $row1 = $this->db->single();

        $this->db->query('SELECT * FROM reservationreview WHERE reservationID = :reservationID');
        $this->db->bind(':reservationID', $reservationID);
        if ($this->db->single()) {
            $row1->review = 1;
        } else {
            $row1->review = 0;
        }

        $this->db->query('SELECT * FROM orderitem WHERE orderNO = :orderNO');
        $this->db->bind(':orderNO', $row1->orderID);
        $row2 = $this->db->resultSet();

        foreach ($row2 as $item) {
            $this->db->query('SELECT * FROM menuitem WHERE itemID = :itemID');
            $this->db->bind(':itemID', $item->itemID);
            $row3 = $this->db->single();
            $item->itemName = $row3->itemName;
            $item->imagePath = $row3->imagePath;
        }

        foreach ($row2 as $item) {
            $this->db->query('SELECT * FROM menuprices WHERE ItemID = :itemID AND itemSize = :size');
            $this->db->bind(':itemID', $item->itemID);
            $this->db->bind(':size', $item->size);
            $row4 = $this->db->single();
            if ($row4) {
                $item->price = $row4->ItemPrice;
            } else {
                $this->db->query('SELECT * FROM menuprices WHERE ItemID = :itemID AND itemSize = "Regular"');
                $this->db->bind(':itemID', $item->itemID);
                $row5 = $this->db->single();
                $item->price = $row5->ItemPrice;
            }
        }




        $data[0] = $row1;
        $data[1] = $row2;

        return $data;
    }

    // Add a function to count the total number of reservations based on search criteria
    public function getTotalReservationCount($user_id)
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservation WHERE customerID = :user_id ORDER BY date ASC');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row->count;
    }

    public function getReservationWithSearch($user_id, $limit = 10, $offset = 0, $search)
    {
        $this->db->query('SELECT * FROM reservation WHERE customerID = :user_id AND (status LIKE :search OR numOfPeople LIKE :search) ORDER BY date ASC LIMIT :offset, :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $this->db->bind(':search', "%$search%");
        $results = $this->db->resultSet();
        return $results;
    }

    public function getTotalReservationCountWithSearch($user_id, $search)
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservation WHERE customerID = :user_id AND (status LIKE :search OR numOfPeople LIKE :search) order by date ASC');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':search', "%$search%");
        $row = $this->db->single();
        return $row->count;
    }


    public function cancelReservation($reservationID, $orderID)
    {
        $this->db->query('UPDATE reservation SET status = "Cancelled" WHERE reservationID = :reservationID');
        $this->db->bind(':reservationID', $reservationID);
        if ($this->db->execute()) {
            $this->db->query('UPDATE orders SET status = "Cancelled" WHERE orderItemID = :orderID');
            $this->db->bind(':orderID', $orderID);
            if ($this->db->execute()) {
                $this->db->query('UPDATE orderitem SET status = "Cancelled" WHERE orderNO = :orderID');
                $this->db->bind(':orderID', $orderID);
                $this->db->execute();
                if ($this->db->execute()) {
                    $this->db->query('DELETE FROM slots WHERE reservationID = :reservationID');
                    $this->db->bind(':reservationID', $reservationID);
                    $this->db->execute();
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function refundRequest($reservationID, $amount)
    {
        $this->db->query('UPDATE reservation SET status = "Refund Requested" WHERE reservationID = :reservationID');
        $this->db->bind(':reservationID', $reservationID);
        $row = $this->db->execute();
        if ($row) {
            $this->db->query('UPDATE payment SET status = "Refund Requested" WHERE reservationID = :reservationID');
            $this->db->bind(':reservationID', $reservationID);
            if ($this->db->execute()) {
                $this->db->query(('INSERT INTO refundrequest (reservationID , amount ,status ) VALUES (:reservationID , :amount ,"Pending")'));
                $this->db->bind(':reservationID', $reservationID);
                $this->db->bind(':amount', $amount);
                if ($this->db->execute()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }





    public function addReservation($data)
    {
        $this->db->query('INSERT INTO reservation (customerID, tableID, packageID, date, reservationStartTime,amount, reservationEndTime, numOfPeople,invoiceID) VALUES (:customerID, :tableID, :packageID, :date, :reservationStartTime,:amount, :reservationEndTime, :numOfPeople, InvoiceID)');
        $this->db->bind(':customerID', $data['customerID']);
        $this->db->bind(':tableID', $data['tableID']);
        $this->db->bind(':packageID', $data['packageID']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':reservationStartTime', $data['reservationStartTime']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':reservationEndTime', $data['reservationEndTime']);
        $this->db->bind(':numOfPeople', $data['numOfPeople']);
        // Execute the query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAddedReservationID($data)
    {
        $this->db->query('SELECT reservationID FROM reservation WHERE customerID = :customerID AND tableID = :tableID AND packageID = :packageID AND date = :date AND reservationStartTime = :reservationStartTime AND numOfPeople = :numOfPeople order by reservationID desc limit 1');
        $this->db->bind(':customerID', $data['customerID']);
        $this->db->bind(':tableID', $data['tableID']);
        $this->db->bind(':packageID', $data['packageID']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':reservationStartTime', $data['reservationStartTime']);
        // $this->db->bind(':reservationEndTime', $data['reservationEndTime']);
        $this->db->bind(':numOfPeople', $data['numOfPeople']);
        $row = $this->db->single();
        return $row->reservationID;
    }

    public function addToSlot($reservationID, $data, $slot)
    {
        $this->db->query('INSERT INTO slots (reservationID, slot, date, noofpeople) VALUES (:reservationID, :slot, :date, :numOfPeople)');
        $this->db->bind(':reservationID', $reservationID);
        $this->db->bind(':slot', $slot);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':numOfPeople', $data['numOfPeople']);
        // Execute the query
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function makePayment($data)
    {
        $this->db->query('INSERT INTO payment (invoiceID , reservationID, paymentMethod, amount ) VALUES ( :invoiceID ,:reservationID, :paymentMethod, :amount)');
        $invoiceIDToAdd = $data['reservationID'] . '_INV';
        $this->db->bind(':invoiceID', $invoiceIDToAdd);
        $this->db->bind(':reservationID', $data['reservationID']);
        $this->db->bind(':paymentMethod', $data['paymentMethod']);
        $this->db->bind(':amount', $data['amount']);
        if ($this->db->execute()) { {
                $this->db->query('UPDATE reservation SET invoiceID = :invoiceID WHERE reservationID = :reservationID');
                $this->db->bind(':invoiceID', $data['invoiceID']);
                $this->db->bind(':reservationID', $data['reservationID']);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    public function createOrder($data)
    {
        $this->db->query('INSERT INTO orders (orderItemID,reservationID , status ) VALUES (:orderItemID,:reservationID , "Pending")');
        $this->db->bind(':reservationID', $data['reservationID']);
        $orderIdToAdd = $data['reservationID'] . '_ORD';
        $this->db->bind(':orderItemID', $orderIdToAdd);

        if ($this->db->execute()) {

            $this->db->query('UPDATE reservation SET orderID = :orderID WHERE reservation.reservationID = :reservationID;');
            $this->db->bind(':orderID', $orderIdToAdd);
            $this->db->bind(':reservationID', $data['reservationID']);
            $this->db->execute();

            $items = $data['orderItems'];
            foreach ($items as $item) {

                $this->db->query('INSERT INTO orderitem(orderNO, itemID,size ,quantity , status) VALUES (:orderID, :itemID, :size , :quantity , "Pending")');
                $this->db->bind(':orderID', $orderIdToAdd);
                $this->db->bind(':itemID', $item['itemID']);
                $this->db->bind(':size', $item['size']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    public function markPaid($reservationID)
    {
        $this->db->query('UPDATE reservation SET status = "Paid" WHERE reservationID = :reservationID');
        $this->db->bind(':reservationID', $reservationID);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getSuiteReviews()
    {
        $this->db->query('SELECT suite , COUNT(suitRating) as "totalReviews" , ROUND(SUM(suitRating) / COUNT(suitRating)) as "avgReviews"  FROM reservationreview GROUP BY suite');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getFoodReviews()
    {
        $this->db->query('SELECT  itemID , COUNT(stars) as "count" , ROUND(SUM(stars) / COUNT(stars)) as "stars" FROM reviewfood GROUP BY itemID');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getAllReviews($limit = 10, $offset = 0)
    {
        $this->db->query(' SELECT * FROM reservationreview ORDER BY reviewID DESC LIMIT :offset, :limit');
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $results = $this->db->resultSet();
        foreach ($results as $review) {
            $this->db->query('SELECT name  , imagePath from users WHERE user_id = :user_id');
            $this->db->bind(':user_id', $review->customerID);
            $row = $this->db->single();
            $review->name = $row->name;
            $review->imagePath = $row->imagePath;
        }
        return $results;
    }

    public function getTotalReviewCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservationreview');
        $row = $this->db->single();
        return $row->count;
    }

    public function getSuites()
    {
        $this->db->query('SELECT packageID , packageName FROM package');
        $results = $this->db->resultSet();
        return $results;
    }


    public function getMenus()
    {
        $this->db->query("SELECT mi.itemID, mi.itemName, mi.imagePath , mi.category_ID, GROUP_CONCAT(mp.itemSize ORDER BY mp.itemSize SEPARATOR ', ') AS Sizes, GROUP_CONCAT(mp.ItemPrice ORDER BY mp.itemSize SEPARATOR ', ') AS Prices FROM menuitem mi JOIN menuprices mp ON mi.itemID = mp.ItemID WHERE mi.delete_status = 0 AND mi.hidden = 0 GROUP BY mi.itemID ORDER BY mi.itemID;");
        $results = $this->db->resultSet();
        return $results;
    }

    public function getRemainingSlots($date)
    {
        $this->db->query('SELECT * FROM reservation WHERE date = :date');
        $this->db->bind(':date', $date);
        $results = $this->db->resultSet();
        return $results;
    }



    public function getReservationStatus()
    {
        $this->db->query('SELECT * FROM reservationStatus');
        $results = $this->db->resultSet();
        return $results;
    }

    public function getReservationWithStatus($user_id, $limit = 10, $offset = 0, $status)
    {
        $this->db->query('SELECT * FROM reservation WHERE customerID = :user_id AND status = :status ORDER BY date ASC LIMIT :offset, :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $this->db->bind(':status', $status);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getTotalReservationCountWithStatus($user_id, $status)
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservation WHERE customerID = :user_id AND status = :status ORDER BY date ASC');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':status', $status);
        $row = $this->db->single();
        return $row->count;
    }

    public function getReservationWithDateRange($user_id, $limit = 10, $offset = 0, $startDate, $endDate)
    {
        $this->db->query('SELECT * FROM reservation WHERE customerID = :user_id AND date BETWEEN :startDate AND :endDate ORDER BY date ASC LIMIT :offset, :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $results = $this->db->resultSet();
        return $results;
    }
    public function getTotalReservationCountWithDateRange($user_id, $startDate, $endDate)
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservation WHERE customerID = :user_id AND date BETWEEN :startDate AND :endDate ORDER BY date ASC');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $row = $this->db->single();
        return $row->count;
    }

    public function getMaxDate()
    {
        $this->db->query('SELECT MAX(date) as maxDate FROM reservation');
        $row = $this->db->single();
        return $row->maxDate;
    }

    public function getMinDate()
    {
        $this->db->query('SELECT MIN(date) as minDate FROM reservation');
        $row = $this->db->single();
        return $row->minDate;
    }

    public function getReservationWithStatusAndDateRange($user_id, $limit = 10, $offset = 0, $status, $startDate, $endDate)
    {
        $this->db->query('SELECT * FROM reservation WHERE customerID = :user_id AND status = :status AND date BETWEEN :startDate AND :endDate ORDER BY date ASC LIMIT :offset, :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $this->db->bind(':status', $status);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getTotalReservationCountWithStatusAndDateRange($user_id, $status, $startDate, $endDate)
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservation WHERE customerID = :user_id AND status = :status AND date BETWEEN :startDate AND :endDate ORDER BY date ASC');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':status', $status);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $row = $this->db->single();
        return $row->count;
    }

    public function getReservationWithSearchStatusAndDateRange($user_id, $limit = 10, $offset = 0, $search, $status, $startDate, $endDate)
    {
        $this->db->query('SELECT * FROM reservation WHERE customerID = :user_id AND (status LIKE :search OR numOfPeople LIKE :search) AND date BETWEEN :startDate AND :endDate ORDER BY date ASC LIMIT :offset, :limit');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $this->db->bind(':search', "%$search%");
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getTotalReservationCountWithSearchStatusAndDateRange($user_id, $search, $status, $startDate, $endDate)
    {
        $this->db->query('SELECT COUNT(*) as count FROM reservation WHERE customerID = :user_id AND (status LIKE :search OR numOfPeople LIKE :search) AND date BETWEEN :startDate AND :endDate ORDER BY date ASC');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':search', "%$search%");
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $row = $this->db->single();
        return $row->count;
    }

    public function getSlots($date)
    {
        $this->db->query('SELECT slot , SUM(noofpeople) as slotCapacity FROM slots WHERE date = :date GROUP BY slot ORDER BY slot;');
        $this->db->bind(':date', $date);
        $results = $this->db->resultSet();
        return $results;
    }


    public function getSuiteCapacities()
    {
        $this->db->query('SELECT packageID, SUM(capacity) AS total_capacity
        FROM `tables`
        GROUP BY packageID;');
        $results = $this->db->resultSet();
        return $results;
    }


    public function submitReservationReview($data)
    {

        $this->db->query('INSERT INTO reservationreview (customerID, overallrating, suitRating, comment, reservationID, suite) VALUES (:customerID, :overallrating, :suitRating, :comment, :reservationID, :suite )');
        $this->db->bind(':customerID', $data['customerID']);
        $this->db->bind(':overallrating', $data['overallRating']);
        $this->db->bind(':suitRating', $data['suitRating']);
        $this->db->bind(':comment', $data['comment']);
        $this->db->bind(':reservationID', $data['reservationID']);
        $this->db->bind(':suite', $data['suite']);

        if ($this->db->execute()) {
            $reviewID = $this->db->lastInsertId();
            $items = $data['reviewChecked'];
            foreach ($items as $item) {

                $this->db->query('INSERT INTO reviewfood(reviewID, itemID,stars) VALUES (:reviewID, :itemID, :stars)');
                $this->db->bind(':reviewID', $reviewID);
                $this->db->bind(':itemID', $item['ItemID']);
                $this->db->bind(':stars', $item['rating']);
                $this->db->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    public function getSlotRemainingForSuites($date)
    {
        $this->db->query('SELECT r.packageID,
        s.slot,
        SUM(s.noofpeople) AS total_people
    FROM 
        slots s
    JOIN 
        reservation r ON s.reservationID = r.reservationID
    WHERE 
        r.date = :date 
    GROUP BY 
        r.packageID, s.slot
    ORDER BY 
        r.packageID, s.slot;');
        $this->db->bind(':date', $date);
        $results = $this->db->resultSet();
        return $results;
        
    }
}
