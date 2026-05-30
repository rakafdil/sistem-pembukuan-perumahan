import { z } from "zod";

const penghuniAktifSchema = z.object({
  id: z.string(),
  nama_lengkap: z.string(),
  nomor_telepon: z.string(),
});

export const rumahSchema = z.object({
  id: z.string(),
  blok_nomor: z.string(),
  status_huni: z.enum(["dihuni", "kosong"]),
  penghuni_aktif: penghuniAktifSchema.nullable(),
});

export type Rumah = z.infer<typeof rumahSchema>;

export const formRumahSchema = z.object({
  blok_nomor: z
    .string()
    .min(1, { message: "Blok dan nomor rumah harus diisi" })
    .max(50, { message: "Maksimal 50 karakter" }),
  penghuni_id: z.string().optional(),
  tanggal_mulai: z.date().optional()
});

export type FormRumahDTO = z.infer<typeof formRumahSchema>;

export const historiSchema = z.object({
  penghuni_id: z.string(),
  rumah_id: z.string(),
  tanggal_mulai: z.string(),
  tanggal_selesai: z.string(),
});

export type Histori = z.infer<typeof historiSchema>;

export const assignRumahSchema = z.object({
  penghuni_id: z.string(),
  tanggal_mulai: z.date(),
});

export type AssignRumahDTO = z.infer<typeof assignRumahSchema>;

export const unassignRumahSchema = z.object({
  tanggal_selesai: z.date(),
});

export type UnassignRumahDTO = z.infer<typeof unassignRumahSchema>;
