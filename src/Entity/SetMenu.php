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

    public function __construct()
    {
        $this->courseCategory = new ArrayCollection();
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
}
