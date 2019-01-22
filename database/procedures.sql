DELIMITER $$
CREATE DEFINER=`spaand1q`@`localhost` PROCEDURE `BoughtInLastDay`(IN `user` INT)
    NO SQL
SELECT OrderHistory.userId, OrderHistory.productName,COUNT(OrderHistory.orderId) AS NumberOfOrders FROM OrderHistory WHERE OrderHistory.userId = user and OrderHistory.orderMoment BETWEEN DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY) AND CURRENT_TIMESTAMP AND OrderHistory.deleted = 0 GROUP BY productName$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`spaand1q`@`localhost` PROCEDURE `BoughtInTotal`(IN `user` INT)
    NO SQL
SELECT OrderHistory.userId, OrderHistory.productName,COUNT(OrderHistory.orderId) AS NumberOfOrders FROM OrderHistory WHERE OrderHistory.userId = user AND OrderHistory.deleted = 0 GROUP BY productName$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`spaand1q`@`localhost` PROCEDURE `CreateOrderForUser`(IN `product` INT, IN `user` INT, IN `during` BOOLEAN, IN `orderedBy` INT)
    NO SQL
INSERT INTO OrderHistory (productId, productName, orderMoment, during, price, userId, orderedBy)
SELECT product, name, CURRENT_TIMESTAMP, during, IF(during=1, priceDuring, priceOutside), user, orderedBy 
FROM Products
WHERE active=1 AND productId = product$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`spaand1q`@`localhost` PROCEDURE `SelectToPayFromUser`(IN `User_id` INT)
    NO SQL
SELECT * FROM ToPay WHERE userId = User_id$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`spaand1q`@`localhost` PROCEDURE `SendUserMessage`(IN `receiver` INT, IN `sentmessage` VARCHAR(255))
    NO SQL
INSERT into messages (receiverUserId, message, opened, messageMoment)
SELECT receiver, sentmessage, '0', CURRENT_TIMESTAMP$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`spaand1q`@`localhost` PROCEDURE `UpdateToPay`(IN `product` INT, IN `user` INT, IN `during` BOOLEAN)
    NO SQL
update ToPay TP
left join Products P on TP.userId = user
set TP.totalPay = IF(during=1, priceDuring, priceOutside) + TP.totalPay, TP.toPay = TP.totalPay - TP.payed
WHERE TP.userId = user and P.productId = product$$
DELIMITER ;
