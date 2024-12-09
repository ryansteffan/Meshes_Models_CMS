/*
 Name: Ryan Steffan
 Date: December 3, 2024
 Description: Generates a report of the orders that have been made to Direct Deserts.
 */
SELECT o.order_id AS 'Order ID',
    CONCAT(c.first_name, ' ', c.last_name) AS 'Customer Name',
    p.name AS 'Product name',
    p.price AS 'Product Price',
    op.item_quantity AS 'Item Quantity',
    op.item_quantity * p.price AS 'Order Total'
FROM Orders o
    JOIN Customers c ON o.cust_id = c.cust_id
    JOIN OrdersProducts op ON o.order_id = op.order_id
    JOIN Products p ON op.product_id = p.product_id;