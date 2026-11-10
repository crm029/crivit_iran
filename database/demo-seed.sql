-- Optional demo catalog seed: import after schema.sql on a fresh database.
USE crivit_iran;
INSERT INTO categories (name,slug,description,sort_order) VALUES
('پوشاک ورزشی','sportswear','پوشاک مناسب تمرین و فعالیت روزانه',1),
('تجهیزات تمرین','training-equipment','ابزارهای تمرین در خانه و باشگاه',2),
('کمپینگ و طبیعت‌گردی','camping-outdoor','تجهیزات سفر و فضای باز',3),
('توپ و بازی‌های ورزشی','balls-games','توپ و لوازم بازی‌های ورزشی',4),
('کفش و پیاده‌روی','shoes-walking','کفش و لوازم پیاده‌روی',5),
('لوازم جانبی','accessories','لوازم جانبی ورزشی',6);
INSERT INTO products (name,slug,sku,summary,description,price,stock_quantity,stock_status,status,is_featured,published_at) VALUES
('تی‌شرت ورزشی تنفسی','breathable-sport-tshirt','DEMO-001','پارچه سبک و مناسب تمرین روزانه','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',590000,12,'in_stock','published',1,NOW()),
('شلوار ورزشی سبک','light-sport-pants','DEMO-002','مناسب تمرین و استفاده روزمره','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',890000,10,'in_stock','published',0,NOW()),
('هودی تمرینی','training-hoodie','DEMO-003','لایه گرم سبک برای فعالیت روزانه','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',1250000,8,'in_stock','published',1,NOW()),
('کاپشن بادگیر','windbreaker-jacket','DEMO-004','مناسب فعالیت در فضای باز','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',1890000,6,'in_stock','published',1,NOW()),
('دمبل روکش‌دار ۵ کیلویی','coated-dumbbell-5kg','DEMO-005','مناسب تمرین قدرتی','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',980000,15,'in_stock','published',0,NOW()),
('کش مقاومتی تمرین','resistance-band','DEMO-006','ابزار سبک تمرین در خانه','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',320000,25,'in_stock','published',0,NOW()),
('مت یوگا ضدلغزش','non-slip-yoga-mat','DEMO-007','زیرانداز مناسب تمرین و کشش','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',760000,11,'in_stock','published',1,NOW()),
('طناب ورزشی حرفه‌ای','pro-jump-rope','DEMO-008','مناسب تمرین هوازی','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',280000,20,'in_stock','published',0,NOW()),
('کوله‌پشتی طبیعت‌گردی','outdoor-backpack','DEMO-009','فضای مناسب وسایل سفر','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',2150000,7,'in_stock','published',1,NOW()),
('چراغ پیشانی شارژی','rechargeable-headlamp','DEMO-010','روشنایی دست‌آزاد برای کمپینگ','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',690000,9,'in_stock','published',0,NOW()),
('قمقمه ورزشی','sport-water-bottle','DEMO-011','همراه تمرین و پیاده‌روی','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',240000,30,'in_stock','published',0,NOW()),
('کیسه خواب کمپینگ','camping-sleeping-bag','DEMO-012','تجهیزات پایه اقامت در طبیعت','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',2650000,4,'in_stock','published',1,NOW()),
('توپ فوتبال تمرینی','training-football','DEMO-013','مناسب تمرین و بازی دوستانه','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',850000,13,'in_stock','published',0,NOW()),
('توپ بسکتبال','basketball-ball','DEMO-014','مناسب تمرین و بازی','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',790000,10,'in_stock','published',0,NOW()),
('دستکش بدنسازی','gym-training-gloves','DEMO-015','کمک به گرفتن بهتر تجهیزات','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',410000,18,'in_stock','published',0,NOW()),
('زیرانداز سفری','folding-camping-mat','DEMO-016','سبک و مناسب سفر','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',560000,14,'in_stock','published',0,NOW()),
('عینک آفتابی ورزشی','sport-sunglasses','DEMO-017','طراحی مناسب فعالیت بیرونی','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',1120000,5,'in_stock','published',0,NOW()),
('کفش پیاده‌روی','walking-shoes','DEMO-018','مناسب استفاده روزانه','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',2390000,6,'in_stock','published',1,NOW()),
('مچ‌بند ورزشی','sport-wristband','DEMO-019','اکسسوری سبک ورزشی','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',190000,22,'in_stock','published',0,NOW()),
('کیف ورزشی','sports-duffel-bag','DEMO-020','برای حمل وسایل تمرین','محصول نمونه نمایشی؛ مشخصات نهایی توسط مدیر فروشگاه تکمیل می‌شود.',980000,9,'in_stock','published',0,NOW());
INSERT INTO product_categories (product_id,category_id)
SELECT p.id,c.id FROM products p
JOIN categories c ON c.slug = CASE
 WHEN CAST(SUBSTRING(p.sku,6) AS UNSIGNED) BETWEEN 1 AND 4 THEN 'sportswear'
 WHEN CAST(SUBSTRING(p.sku,6) AS UNSIGNED) BETWEEN 5 AND 8 THEN 'training-equipment'
 WHEN CAST(SUBSTRING(p.sku,6) AS UNSIGNED) BETWEEN 9 AND 12 THEN 'camping-outdoor'
 WHEN CAST(SUBSTRING(p.sku,6) AS UNSIGNED) BETWEEN 13 AND 14 THEN 'balls-games'
 WHEN CAST(SUBSTRING(p.sku,6) AS UNSIGNED) BETWEEN 17 AND 18 THEN 'shoes-walking'
 ELSE 'accessories' END
WHERE p.sku LIKE 'DEMO-%';
