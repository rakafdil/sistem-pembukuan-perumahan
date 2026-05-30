import { useEffect, useState } from "react";
import { useForm, Controller } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";

import {
  Dialog,
  DialogContent,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

import { Checkbox } from "@/components/ui/checkbox";

import { Upload } from "lucide-react";
import { toast } from "sonner";

import {
  type Penghuni,
  formPenghuniSchema,
  type FormPenghuniDTO,
} from "../types";

import { useCreatePenghuni, useUpdatePenghuni } from "../hooks/usePenghuni";

interface Props {
  open: boolean;
  onOpenChange: (v: boolean) => void;
  editing: Penghuni | null;
}

export function PenghuniDialog({ open, onOpenChange, editing }: Props) {
  const createMutation = useCreatePenghuni();
  const updateMutation = useUpdatePenghuni();

  const {
    register,
    handleSubmit,
    control,
    reset,
    formState: { errors },
  } = useForm<FormPenghuniDTO>({
    resolver: zodResolver(formPenghuniSchema),

    defaultValues: {
      nama_lengkap: "",
      nomor_telepon: "",
      status_penghuni: "tetap",
      status_menikah: false,
      foto_ktp: undefined,
    },
  });

  const [fotoFile, setFotoFile] = useState<File | null>(null);
  const [fotoPreview, setFotoPreview] = useState<string | null>(null);

  useEffect(() => {
    if (editing) {
      reset({
        nama_lengkap: editing.nama_lengkap ?? "",
        nomor_telepon: editing.nomor_telepon ?? "",
        status_penghuni: editing.status_penghuni ?? "tetap",
        status_menikah: editing.status_menikah ?? false,
      });

      // eslint-disable-next-line react-hooks/set-state-in-effect
      setFotoPreview(editing.foto_ktp_url ?? null);
    } else {
      reset({
        nama_lengkap: "",
        nomor_telepon: "",
        status_penghuni: "tetap",
        status_menikah: false,
      });

      setFotoPreview(null);
      setFotoFile(null);
    }
  }, [editing, open, reset]);

  function handleFile(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];

    if (!file) return;

    setFotoFile(file);
    setFotoPreview(URL.createObjectURL(file));
  }

  function onSubmit(data: FormPenghuniDTO) {
    const formData = new FormData();

    formData.append("nama_lengkap", data.nama_lengkap);
    formData.append("status_penghuni", data.status_penghuni);
    formData.append("status_menikah", data.status_menikah ? "1" : "0");

    if (data.nomor_telepon) {
      formData.append("nomor_telepon", data.nomor_telepon);
    }

    if (fotoFile) {
      formData.append("foto_ktp", fotoFile);
    }

    if (editing) {
      updateMutation.mutate(
        {
          id: editing.id,
          data: formData,
        },
        {
          onSuccess: () => {
            toast.success("Penghuni berhasil diperbarui");

            onOpenChange(false);
          },

          onError: () => {
            toast.error("Gagal memperbarui penghuni");
          },
        },
      );
    } else {
      createMutation.mutate(formData, {
        onSuccess: () => {
          toast.success("Penghuni berhasil ditambahkan");

          onOpenChange(false);
        },

        onError: () => {
          toast.error("Gagal menambahkan penghuni");
        },
      });
    }
  }

  const isPending = createMutation.isPending || updateMutation.isPending;

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>
            {editing ? "Edit Penghuni" : "Tambah Penghuni"}
          </DialogTitle>
        </DialogHeader>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
          <div>
            <Label>
              Nama Lengkap <span className="text-red-500">*</span>
            </Label>

            <Input className="mt-1.5" {...register("nama_lengkap")} />

            {errors.nama_lengkap && (
              <p className="mt-1 text-sm text-red-500">
                {errors.nama_lengkap.message}
              </p>
            )}
          </div>

          <div>
            <Label>Nomor Telepon</Label>

            <Input className="mt-1.5" {...register("nomor_telepon")} />

            {errors.nomor_telepon && (
              <p className="mt-1 text-sm text-red-500">
                {errors.nomor_telepon.message}
              </p>
            )}
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div>
              <Label>
                Status Penghuni <span className="text-red-500">*</span>
              </Label>

              <Controller
                control={control}
                name="status_penghuni"
                render={({ field }) => (
                  <Select value={field.value} onValueChange={field.onChange}>
                    <SelectTrigger className="mt-1.5">
                      <SelectValue />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem value="tetap">Tetap</SelectItem>

                      <SelectItem value="kontrak">Kontrak</SelectItem>
                    </SelectContent>
                  </Select>
                )}
              />
            </div>

            <div className="flex items-end pb-2">
              <label className="flex cursor-pointer items-center gap-2">
                <Controller
                  control={control}
                  name="status_menikah"
                  render={({ field }) => (
                    <Checkbox
                      checked={field.value}
                      onCheckedChange={(v) => field.onChange(!!v)}
                    />
                  )}
                />

                <span className="text-sm">Sudah Menikah</span>
              </label>
            </div>
          </div>

          <div>
            <Label>Foto KTP (Max 2MB)</Label>

            <div className="mt-1.5 flex items-center gap-3">
              {fotoPreview ? (
                <img
                  src={fotoPreview}
                  alt="KTP"
                  className="h-16 w-24 rounded border object-cover"
                />
              ) : (
                <div className="text-muted-foreground flex h-16 w-24 items-center justify-center rounded border border-dashed text-xs">
                  No file
                </div>
              )}

              <label className="cursor-pointer">
                <input
                  type="file"
                  accept="image/*"
                  className="hidden"
                  onChange={handleFile}
                />

                <Button type="button" variant="outline" asChild>
                  <span>
                    <Upload className="mr-2 h-4 w-4" />
                    Pilih Foto
                  </span>
                </Button>
              </label>
            </div>
          </div>

          <DialogFooter>
            <Button
              type="button"
              variant="outline"
              onClick={() => onOpenChange(false)}
              disabled={isPending}
            >
              Batal
            </Button>

            <Button type="submit" disabled={isPending}>
              {isPending ? "Menyimpan..." : "Simpan"}
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  );
}
