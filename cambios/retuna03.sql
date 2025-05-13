BEGIN

SELECT p.id, 
       p.producto, 
       SUM(v.cantidad) AS cantidad, 
       SUM(ROUND(v.total, 2)) AS total_venta
FROM ventas v
INNER JOIN productos p ON v.id_producto = p.id
GROUP BY p.id, p.producto
ORDER BY total_venta DESC
LIMIT 10;

END
