<?php

namespace App\Entity;

use App\Entity\Trait\ProductTrait;
use App\Entity\Trait\TitleTrait;
use App\Repository\SetMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SetMenuRepository::class)]
class SetMenu
{

    use TitleTrait;
    use ProductTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: CourseCategory::class, inversedBy: 'setMenus')]
    private Collection $courseCategory;

    #[ORM\OneToMany(mappedBy: 'setMenu', targetEntity: Menu::class)]
    private Collection $menus;

    public function __construct()
    {
        $this->courseCategory = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CourseCategory>
     */
    public function getCourseCategory(): Collection
    {
        return $this->courseCategory;
    }

    public function addCourseCategory(CourseCategory $courseCategory): self
    {
        if (!$this->courseCategory->contains($courseCategory)) {
            $this->courseCategory->add($courseCategory);
        }

        return $this;
    }

    public function removeCourseCategory(CourseCategory $courseCategory): self
    {
        $this->courseCategory->removeElement($courseCategory);

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->setSetMenu($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getSetMenu() === $this) {
                $menu->setSetMenu(null);
            }
        }

        return $this;
    }
}
