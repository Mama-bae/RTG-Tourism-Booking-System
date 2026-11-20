-- Run this ONCE only if you already imported the earlier project schema.
USE rtg_tourism;
ALTER TABLE bookings ADD COLUMN booking_reference VARCHAR(20) NULL;
UPDATE bookings SET booking_reference = CONCAT('RTG-', LPAD(booking_id, 6, '0')) WHERE booking_reference IS NULL;
ALTER TABLE bookings MODIFY booking_reference VARCHAR(20) NOT NULL;
ALTER TABLE bookings ADD UNIQUE KEY uq_booking_reference (booking_reference);
