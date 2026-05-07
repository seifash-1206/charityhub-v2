import * as React from "react"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "../../lib/utils"

const buttonVariants = cva(
  "inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50",
  {
    variants: {
      variant: {
        default: "bg-primary-900 text-white hover:bg-primary-800 dark:bg-primary-800 dark:hover:bg-primary-700",
        destructive: "bg-red-600 text-white hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-700",
        outline:
          "border border-primary-300 bg-background hover:bg-primary-100 dark:border-primary-700 dark:hover:bg-primary-900/20",
        secondary: "bg-secondary-100 text-secondary-900 hover:bg-secondary-200 dark:bg-secondary-700 dark:text-secondary-100 dark:hover:bg-secondary-600",
        ghost: "hover:bg-primary-100 dark:hover:bg-primary-900/20",
        link: "text-primary-900 underline-offset-4 hover:underline dark:text-primary-400",
      },
      size: {
        default: "h-10 px-4 py-2",
        sm: "h-9 rounded-md px-3 text-xs",
        lg: "h-11 rounded-md px-8",
        icon: "h-10 w-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, ...props }, ref) => (
    <button
      className={cn(buttonVariants({ variant, size, className }))}
      ref={ref}
      {...props}
    />
  )
)
Button.displayName = "Button"

export { Button, buttonVariants }
